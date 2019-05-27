import * as functions from 'firebase-functions';

// // Start writing Firebase Functions
// // https://firebase.google.com/docs/functions/typescript
//
export const helloWorld = functions.https.onRequest((request, response) => {
 response.send("Hello from Firebase!");
});

export const scheduledFunction = functions.pubsub.schedule('every day 08:00').onRun((context) => {
    console.log('This will be run every day minutes!');
});