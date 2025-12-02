importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBJSzeiB7BoW7Q55krAeAo1zjhxQAIuVBU",
    authDomain: "kari-ng.firebaseapp.com",
    projectId: "kari-ng",
    storageBucket: "kari-ng.firebasestorage.app",
    messagingSenderId: "418710741971",
    appId: "1:418710741971:web:3fdf23cfcfea838bec0526",
    measurementId: "G-NZ146X1FVS"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    return self.registration.showNotification(payload.data.title, {
        body: payload.data.body ? payload.data.body : '',
        icon: payload.data.icon ? payload.data.icon : ''
    });
});