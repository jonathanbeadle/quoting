// Initialize Firebase and Firestore
const firebaseConfig = {
    apiKey: "AIzaSyAjAOuXElBMuBCEDRcHK6j1XQ1NK9nA6Y4",
    authDomain: "quoting-c3463.firebaseapp.com",
    projectId: "quoting-c3463",
    storageBucket: "quoting-c3463.appspot.com",
    messagingSenderId: "549875159098",
    appId: "1:549875159098:web:28316c944261eafb21e503"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();

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

    const emailOutput = document.getElementById('emailOutput');
    if (emailOutput) {
        emailOutput.innerHTML = outputHtml;
        document.getElementById('outputSection').classList.remove('hidden');
    } else {
        console.error('Element "emailOutput" not found.');
    }
}

// Function to copy the generated quote to the clipboard
function copyToClipboard() {
    const emailOutputDiv = document.getElementById('emailOutput');
    if (emailOutputDiv) {
        const htmlToCopy = emailOutputDiv.innerHTML;
        navigator.clipboard.writeText(htmlToCopy).then(() => {
            alert("Copied to clipboard!");
        }).catch(err => {
            console.error("Failed to copy: ", err);
        });
    } else {
        console.error('Element "emailOutput" not found.');
    }
}

// Function to reset the form and hide the output section
function resetForm() {
    document.getElementById('quoteForm').reset();
    const outputSection = document.getElementById('outputSection');
    if (outputSection) {
        outputSection.classList.add('hidden');
    } else {
        console.error('Element "outputSection" not found.');
    }
}

// Function to save the generated quote to Firebase Firestore
async function saveQuoteToFirebase() {
    const emailOutput = document.getElementById('emailOutput');
    if (!emailOutput) {
        console.error('Element "emailOutput" not found.');
        return;
    }

    const quoteHtml = emailOutput.innerHTML;
    const quoteNumber = 'Q-' + Math.floor(Math.random() * 1000000);

    try {
        await db.collection('quotes').add({
            quoteNumber,
            quoteHtml,
            timestamp: firebase.firestore.FieldValue.serverTimestamp()
        });
        alert('Quote saved to cloud successfully!');
    } catch (error) {
        console.error('Error saving quote:', error);
    }
}

// Function to display saved quotes from Firestore
async function displaySavedQuotes() {
    const savedQuotesList = document.getElementById('savedQuotesList');
    if (!savedQuotesList) {
        console.error('Element "savedQuotesList" not found.');
        return;
    }

    savedQuotesList.innerHTML = '';

    try {
        const snapshot = await db.collection('quotes').orderBy('timestamp', 'desc').get();
        snapshot.forEach((doc) => {
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
    const quoteOutput = document.getElementById('quoteOutput');
    if (!quoteOutput) {
        console.error('Element "quoteOutput" not found.');
        return;
    }

    try {
        const docSnap = await db.collection('quotes').doc(docId).get();
        if (docSnap.exists()) {
            quoteOutput.innerHTML = docSnap.data().quoteHtml;
        } else {
            alert('Quote not found');
        }
    } catch (error) {
        console.error('Error viewing quote details:', error);
    }
}

// Function to clear all saved quotes from Firebase Firestore
async function clearSavedQuotesFromFirebase() {
    if (confirm('Are you sure you want to delete all quotes?')) {
        try {
            const snapshot = await db.collection('quotes').get();
            const batch = db.batch();
            snapshot.forEach((doc) => batch.delete(doc.ref));
            await batch.commit();
            alert('All quotes deleted successfully!');
            displaySavedQuotes();
        } catch (error) {
            console.error('Error clearing saved quotes:', error);
        }
    }
}

window.onload = () => {
    if (document.getElementById('savedQuotesList')) {
        displaySavedQuotes();
    }
};
