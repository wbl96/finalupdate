document.addEventListener("DOMContentLoaded", function () {
    const itemContainers = document.querySelectorAll(".item-container");

    itemContainers.forEach((container) => {
        const navLink = container.querySelector(".nav-link");
        if (container.classList.contains("active")) {
            const navItem = container.querySelector(".nav-item");
            const previousItemContainer = container.previousElementSibling;
            const nextItemContainer = container.nextElementSibling;

            if (previousItemContainer) {
                const prevNavItem =
                    previousItemContainer.querySelector(".nav-item");
                if (prevNavItem) {
                    prevNavItem.classList.add("right-radius");
                }
            }

            if (nextItemContainer) {
                const nextNavItem =
                    nextItemContainer.querySelector(".nav-item");
                if (nextNavItem) {
                    nextNavItem.classList.add("left-radius");
                }
            }
        }
    });

    // --------------------------Print receipt ---------------------------

    // Get the print button and add an event listener
    const printButtons = document.querySelectorAll(".btn-print");
    if (printButtons)
        printButtons.forEach((btn) => {
            btn.addEventListener("click", function () {
                // id of target leemnt to print
                let id = this.dataset.printId;
                // print target id element
                printReceipt(id);
            });
        });

    // Function to print the receipt
    function printReceipt(targetId) {
        const printContent = document.querySelector(`#${targetId}`).innerHTML;

        // Create a new window for printing
        const printWindow = window.open("", "", "width=1000,height=800");

        // Add the necessary HTML to the new window for proper RTL formatting
        printWindow.document.write(`
      <html dir="rtl" lang="ar">
        <head>
          <title>طباعة الفاتورة</title>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <style>
            body {
              direction: rtl;
              text-align: right;
            }
            .table {
              width: 100%;
              margin-bottom: 20px;
              border-collapse: collapse;
            }
            .table-bordered {
              border: 1px solid #dee2e6;
            }
            .table-bordered th, .table-bordered td {
              border: 1px solid #dee2e6;
              padding: 8px;
              vertical-align: top;
            }
            .modal-title {
              text-align: center;
            }
          </style>
        </head>
        <body>
          <div class="container mt-4">
            ${printContent}
          </div>
        </body>
      </html>
    `);

        // Close the document to trigger the print process
        printWindow.document.close();

        // Wait for the content to load, then print and close the print window
        printWindow.onload = function () {
            printWindow.print();
            printWindow.close();
        };
    }
});

// --------------------------side bar range ---------------------------

// function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
//     const [from, to] = getParsed(fromInput, toInput);
//     fillSlider(fromInput, toInput, "#C6C6C6", "#25daa5", controlSlider);
//     if (from > to) {
//         fromSlider.value = to;
//         fromInput.value = to;
//     } else {
//         fromSlider.value = from;
//     }
// }

// function controlToInput(toSlider, fromInput, toInput, controlSlider) {
//     const [from, to] = getParsed(fromInput, toInput);
//     fillSlider(fromInput, toInput, "#C6C6C6", "#25daa5", controlSlider);
//     setToggleAccessible(toInput);
//     if (from <= to) {
//         toSlider.value = to;
//         toInput.value = to;
//     } else {
//         toInput.value = from;
//     }
// }

// function controlFromSlider(fromSlider, toSlider, fromInput) {
//     const [from, to] = getParsed(fromSlider, toSlider);
//     fillSlider(fromSlider, toSlider, "#C6C6C6", "#25daa5", toSlider);
//     if (from > to) {
//         fromSlider.value = to;
//         fromInput.value = to;
//     } else {
//         fromInput.value = from;
//     }
// }

// function controlToSlider(fromSlider, toSlider, toInput) {
//     const [from, to] = getParsed(fromSlider, toSlider);
//     fillSlider(fromSlider, toSlider, "#C6C6C6", "#25daa5", toSlider);
//     setToggleAccessible(toSlider);
//     if (from <= to) {
//         toSlider.value = to;
//         toInput.value = to;
//     } else {
//         toInput.value = from;
//         toSlider.value = from;
//     }
// }

// function getParsed(currentFrom, currentTo) {
//     const from = parseInt(currentFrom.value, 10);
//     const to = parseInt(currentTo.value, 10);
//     return [from, to];
// }

// function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
//     const rangeDistance = to.max - to.min;
//     const fromPosition = from.value - to.min;
//     const toPosition = to.value - to.min;
//     controlSlider.style.background = `linear-gradient(
//     to right,
//     ${sliderColor} 0%,
//     ${sliderColor} ${(fromPosition / rangeDistance) * 100}%,
//     ${rangeColor} ${(fromPosition / rangeDistance) * 100}%,
//     ${rangeColor} ${(toPosition / rangeDistance) * 100}%,
//     ${sliderColor} ${(toPosition / rangeDistance) * 100}%,
//     ${sliderColor} 100%)`;
// }

// function setToggleAccessible(currentTarget) {
//     const toSlider = document.querySelector("#toSlider");
//     if (Number(currentTarget.value) <= 0) {
//         toSlider.style.zIndex = 2;
//     } else {
//         toSlider.style.zIndex = 0;
//     }
// }

// const fromSlider = document.querySelector("#fromSlider");
// const toSlider = document.querySelector("#toSlider");
// const fromInput = document.querySelector("#fromInput");
// const toInput = document.querySelector("#toInput");
// fillSlider(fromSlider, toSlider, "#C6C6C6", "#25daa5", toSlider);
// setToggleAccessible(toSlider);

// ----------------------------------------------------------------------------------------

// ******************add user img **************

// document
//     .getElementById("userImage")
//     .addEventListener("change", function (event) {
//         const reader = new FileReader();
//         reader.onload = function () {
//             const output = document.getElementById("imagePreview");
//             output.src = reader.result;
//         };
//         reader.readAsDataURL(event.target.files[0]);
//     });

// // ********************Logo***********************
// document
//     .getElementById("siteLogoImage")
//     .addEventListener("change", function (event) {
//         const reader = new FileReader();
//         reader.onload = function () {
//             const output = document.getElementById("siteLogoPreview");
//             output.src = reader.result; // Update the image source to the selected image
//         };
//         reader.readAsDataURL(event.target.files[0]); // Read the selected file
//     });
// // *********************product img ************************

// document
//     .getElementById("productImage")
//     .addEventListener("change", function (event) {
//         const reader = new FileReader();
//         reader.onload = function () {
//             const output = document.getElementById("productImagePreview");
//             output.src = reader.result;
//         };
//         reader.readAsDataURL(event.target.files[0]);
//     });
