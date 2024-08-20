window.onload = function () {
    setTimeout(function () {
        let mediaImages = document.querySelectorAll(".attachment-preview img");

        for (let i = 0; i < mediaImages.length; i++) {
            let currentSrc = mediaImages[i].src;
            let newSrc = "";

            if (currentSrc.includes("0x0/")) {
                newSrc = currentSrc.replace("0x0/", "");
                mediaImages[i].src = newSrc;
            }
        }
    }, 3000);
};
