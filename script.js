// Import Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getFirestore, collection, addDoc, getDocs, doc, getDoc, deleteDoc, query, orderBy, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";

// Firebase Configuration (with your credentials)
const firebaseConfig = {
    apiKey: "AIzaSyAjAOuXElBMuBCEDRcHK6j1XQ1NK9nA6Y4",
    authDomain: "quoting-c3463.firebaseapp.com",
    projectId: "quoting-c3463",
    storageBucket: "quoting-c3463.appspot.com",
    messagingSenderId: "549875159098",
    appId: "1:549875159098:web:28316c944261eafb21e503"
};

// Initialize Firebase and Firestore
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// Helper function to capitalize words
function capitalizeWords(str) {
    return str.replace(/\b\w/g, char => char.toUpperCase());
}

// Helper function to format currency
function formatCurrency(value) {
    return parseFloat(value).toFixed(2);
}

// Function to generate the quote and display it in the output section
function generateQuote() {
    const make = capitalizeWords(document.getElementById('make').value);
    const model = capitalizeWords(document.getElementById('model').value);
    const fundingType = capitalizeWords(document.getElementById('funding_type').value);
    const contractLength = document.getElementById('contract_length').value;
    const annualMileage = document.getElementById('annual_mileage').value;
    const paymentProfile = document.getElementById('payment_profile').value;
    const maintenance = capitalizeWords(document.getElementById('maintenance').value);
    const monthlyCost = formatCurrency(document.getElementById('monthly_cost').value);
    const initialRental = formatCurrency(document.getElementById('initial_rental').value);
    const excessMileageCost = document.getElementById('excess_mileage_cost').value + 'p';
    const processingFee = formatCurrency(document.getElementById('processing_fee').value);
    const quoteNumber = 'Q-' + Math.floor(Math.random() * 1000000);

    const outputHtml = `
        <table style="width: 400px;">
            <tr><td><strong>Quote Number:</strong></td><td>${quoteNumber}</td></tr>
            <tr><td><strong>Vehicle Make:</strong></td><td>${make}</td></tr>
            <tr><td><strong>Vehicle Model:</strong></td><td>${model}</td></tr>
            <tr><td><strong>Funding Type:</strong></td><td>${fundingType}</td></tr>
            <tr><td><strong>Contract Length:</strong></td><td>${contractLength} Months</td></tr>
            <tr><td><strong>Annual Mileage:</strong></td><td>${annualMileage}</td></tr>
            <tr><td><strong>Payment Profile:</strong></td><td>${paymentProfile}</td></tr>
            <tr><td><strong>Maintenance:</strong></td><td>${maintenance}</td></tr>
            <tr><td><strong>Monthly Cost:</strong></td><td>£${monthlyCost}</td></tr>
            <tr><td><strong>Initial Rental:</strong></td><td>£${initialRental}</td></tr>
            <tr><td><strong>Excess Mileage Cost:</strong></td><td>${excessMileageCost}</td></tr>
            <tr><td><strong>Processing Fee:</strong></td><td>£${processingFee}</td></tr>
        </table>`;

    document.getElementById('emailOutput').innerHTML = outputHtml;
    document.getElementById('outputSection').classList.remove('hidden');
}

// Function to copy the generated quote to the clipboard
function copyToClipboard() {
    const emailOutputDiv = document.getElementById('emailOutput');
    const htmlToCopy = emailOutputDiv.innerHTML;

    navigator.clipboard.write([
        new ClipboardItem({ "text/html": new Blob([htmlToCopy], { type: "text/html" }) })
    ]).then(() => {
        alert("Copied to clipboard!");
    }).catch(err => {
        console.error("Failed to copy: ", err);
    });
}

// Function to reset the form and hide the output section
function resetForm() {
    document.getElementById('quoteForm').reset();
    document.getElementById('outputSection').classList.add('hidden');
}

// Function to save the generated quote to Firebase Firestore
async function saveQuoteToFirebase() {
    const quoteHtml = document.getElementById('emailOutput').innerHTML;
    const quoteNumber = 'Q-' + Math.floor(Math.random() * 1000000);

    try {
        await addDoc(collection(db, "quotes"), {
            quoteNumber,
            quoteHtml,
            timestamp: serverTimestamp()
        });
        alert('Quote saved to cloud successfully!');
    } catch (error) {
        console.error('Error saving quote:', error);
    }
}

// Function to display saved quotes from Firestore on the quotes.html page
async function displaySavedQuotes() {
    const savedQuotesList = document.getElementById('savedQuotesList');
    savedQuotesList.innerHTML = '';

    try {
        const q = query(collection(db, "quotes"), orderBy("timestamp", "desc"));
        const querySnapshot = await getDocs(q);
        querySnapshot.forEach((doc) => {
            const quote = doc.data();
            const listItem = document.createElement('li');
            listItem.textContent = quote.quoteNumber;
            listItem.onclick = () => viewQuoteDetails(doc.id);
            savedQuotesList.appendChild(listItem);
        });
    } catch (error) {
        console.error('Error fetching saved quotes:', error);
    }
}

// Function to view the details of a selected quote
async function viewQuoteDetails(docId) {
    try {
        const docRef = doc(db, "quotes", docId);
        const docSnap = await getDoc(docRef);

        if (docSnap.exists()) {
            const quote = docSnap.data();
            document.getElementById('quoteOutput').innerHTML = quote.quoteHtml;
            document.getElementById('quoteDetails').classList.remove('hidden');
            document.getElementById('savedQuotesList').style.display = 'none';
        } else {
            alert('Quote not found');
        }
    } catch (error) {
        console.error('Error viewing quote details:', error);
    }
}

// Function to hide quote details and go back to the list
function hideQuoteDetails() {
    document.getElementById('quoteOutput').innerHTML = '';
    document.getElementById('quoteDetails').classList.add('hidden');
    document.getElementById('savedQuotesList').style.display = 'block';
}

// Function to clear all saved quotes from Firebase Firestore
async function clearSavedQuotesFromFirebase() {
    if (confirm('Are you sure you want to delete all saved quotes?')) {
        try {
            const querySnapshot = await getDocs(collection(db, "quotes"));
            querySnapshot.forEach(async (doc) => {
                await deleteDoc(doc.ref);
            });
            alert('All quotes deleted successfully!');
            displaySavedQuotes();
        } catch (error) {
            console.error('Error clearing saved quotes:', error);
        }
    }
}

// Load saved quotes when the quotes.html page is loaded
window.onload = displaySavedQuotes;