document.addEventListener("DOMContentLoaded", function() {
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const inputMonth = document.getElementById("inputmonth");
    const monthPicker = document.getElementById("monthPicker");
    const monthsContainer = document.getElementById("months");
    const yearElement = document.getElementById("year");
    const prevYearBtn = document.getElementById("prevYear");
    const nextYearBtn = document.getElementById("nextYear");
    const selectCurrentMonthBtn = document.getElementById("selectCurrentMonth");
    let selectedYear = new Date().getFullYear();
    let selectedMonth = null;

    // Render months for the selected year
    function renderMonths() {
        monthsContainer.innerHTML = "";
        months.forEach((month, index) => {
            let monthElement = document.createElement("div");
            monthElement.textContent = month;
            monthElement.classList.add("month");
            monthElement.onclick = function(event) {
                selectMonth(index + 1, month); 
                event.stopPropagation(); 
            };
            monthsContainer.appendChild(monthElement);
        });
    }

    // Select month and update the input field value
    function selectMonth(monthIndex, monthName) {
        selectedMonth = monthIndex;
        inputMonth.value = `${selectedYear}-${selectedMonth < 10 ? '0' : ''}${selectedMonth}`;
        monthPicker.style.display = "none";
    }

    // Change year when clicked on previous/next year buttons
    function changeYear(step, event) {
        event.preventDefault(); 
        event.stopPropagation(); 
        selectedYear += step;
        yearElement.textContent = selectedYear;
        renderMonths();
    }

    prevYearBtn.addEventListener("click", function(event) {
        changeYear(-1, event);
    });
    nextYearBtn.addEventListener("click", function(event) {
        changeYear(1, event);
    });

    // Select current month on button click
    function selectCurrentMonth(event) {
        event.preventDefault();
        event.stopPropagation();

        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();
        
        inputMonth.value = `${currentYear}-${currentMonth < 10 ? '0' : ''}${currentMonth}`;
        monthPicker.style.display = "none";
    }

    selectCurrentMonthBtn.addEventListener("click", function(event) {
        selectCurrentMonth(event);
    });

    // Handle input click to toggle month picker visibility
    inputMonth.addEventListener("click", function(event) {
        monthPicker.style.display = monthPicker.style.display === "block" ? "none" : "block";
        positionMonthPicker();
        event.stopPropagation();
    });

    // Position the month picker based on available space
    function positionMonthPicker() {
        const inputRect = inputMonth.getBoundingClientRect();
        const pickerHeight = monthPicker.offsetHeight;
        const spaceAbove = inputRect.top; // Space available above the input
        const spaceBelow = window.innerHeight - inputRect.bottom; // Space available below the input

        if (spaceAbove >= pickerHeight) {
            monthPicker.classList.add("above");
            monthPicker.classList.remove("below");
        } else if (spaceBelow >= pickerHeight) {
            monthPicker.classList.add("below");
            monthPicker.classList.remove("above");
        } else {
            monthPicker.classList.add("below"); // Default to below if no space is available
            monthPicker.classList.remove("above");
        }
    }

    // Close month picker if click is outside
    document.addEventListener("click", function(event) {
        if (!monthPicker.contains(event.target) && event.target !== inputMonth) {
            monthPicker.style.display = "none";
        }
    });

    yearElement.textContent = selectedYear;
    renderMonths();
});
