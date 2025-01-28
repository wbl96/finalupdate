self.addEventListener("push", (event) => {

    const notif = event.data.json().notification;
    console.log(notif.title);
    Toastify({
        text: notif.title,
        duration: 3000,
        destination: notif.data.url,
        newWindow: true,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "left", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: "linear-gradient(to right, #00b09b, #96c93d)",
        },
        onClick: function(){} // Callback after click
      }).showToast();
      document.getElementById("audio").play();

    event.waitUntil(self.registration.showNotification(notif.title , {
        body: notif.body,
        icon: notif.image,
        data: {
            url: notif.click_action
        }
    }));

});

self.addEventListener("notificationclick", (event) => {

    event.waitUntil(clients.openWindow(event.notification.data.url));

});
