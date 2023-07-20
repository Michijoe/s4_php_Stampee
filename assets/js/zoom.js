document.getElementById("zoom").addEventListener(
    "mousemove",
    function (e) {
        let original = document.getElementById("main-img"),
            magnified = document.getElementById("large-img"),
            style = magnified.style,
            x = e.pageX - this.offsetLeft,
            y = e.pageY - this.offsetTop,
            imgWidth = original.width,
            imgHeight = original.height,
            xperc = (x / imgWidth) * 100,
            yperc = (y / imgHeight) * 100;

        // Add some margin for right edge
        if (x > 0.01 * imgWidth) {
            xperc += 0.15 * xperc;
        }

        // Add some margin for bottom edge
        if (y >= 0.01 * imgHeight) {
            yperc += 0.15 * yperc;
        }

        // Set the background of the magnified image horizontal
        style.backgroundPositionX = xperc - 9 + "%";
        // Set the background of the magnified image vertical
        style.backgroundPositionY = yperc - 9 + "%";

        // Set the background image, color and repeat of the magnified image
        style.backgroundImage = 'url("' + original.src + '")';
        style.backgroundColor = "#F7F8FF";
        style.backgroundRepeat = "no-repeat";

        // Move the magnifying glass with the mouse movement.
        style.left = x - 50 + "px";
        style.top = y - 50 + "px";
    },
    false
);
