// Set languages section animation.
var languagesLevels = document.querySelectorAll(".languageLevel");

function languagesLevelsAnimation() {
    var languageSection = languagesLevels[0];
    var positionFromTop = languageSection.getBoundingClientRect().top;

    if (positionFromTop - windowHeight <= -100)
        for (var i = 0; i < languagesLevels.length; ++i) {
            languagesLevels[i].classList.remove("languageLevelQuick");
            languagesLevels[i].classList.add("languageLevelAnimate" + i);
        }

    if (positionFromTop < -500 || positionFromTop > windowHeight + 250)
        for (var i = 0; i < languagesLevels.length; ++i) {
            languagesLevels[i].classList.add("languageLevelQuick");
            languagesLevels[i].classList.remove("languageLevelAnimate" + i);
        }
}

languagesLevelsAnimation();

window.addEventListener("scroll", languagesLevelsAnimation);
window.addEventListener("resize", languagesLevelsAnimation);


// Pie interestsChart interests section.
window.chartColors = {
    red: "rgb(255, 99, 132)",
    orange: "rgb(255, 159, 64)",
    yellow: "rgb(255, 205, 86)",
    green: "rgb(75, 192, 192)",
    blue: "rgb(54, 162, 235)",
    purple: "rgb(153, 102, 255)",
    grey: "rgb(201, 203, 207)"
};
var config = {
    type: "pie",
    data: {
        datasets: [{
            data: [20, 20, 10, 25, 35],
            backgroundColor: [
                window.chartColors.red,
                window.chartColors.orange,
                window.chartColors.yellow,
                window.chartColors.green,
                window.chartColors.blue,
            ]
        }],
        labels: [
            "Automation",
            "Robotics",
            "Sport",
            "Data analysis",
            "Family and friends"
        ]
    },
    options: {
        aspectRatio: 1,
        animation: {
            duration: 2500,
        }
    }
};

var interestsChart = document.getElementById("interestsChart");
var context = document.getElementById("interestsChartArea").getContext("2d");
var chartAnimationFinished = true;

function chartAnimation() {
    var positionFromTop = interestsChart.getBoundingClientRect().top;
    if (positionFromTop <= -350 || positionFromTop >= windowHeight + 100)
        chartAnimationFinished = true;

    if (!chartAnimationFinished)
        return;

    if (positionFromTop - windowHeight <= -250 && positionFromTop >= -100) {
        chartAnimationFinished = false;
        interestsChartPie = new Chart(context, config);
    }
}

chartAnimation();
window.addEventListener("scroll", chartAnimation);
window.addEventListener("resize", chartAnimation);
