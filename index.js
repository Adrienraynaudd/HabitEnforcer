function setCountDown(limitDate, itemID) {
  var countDownDate = new Date(limitDate);
  countDownDate.setDate(countDownDate.getDate() + 1);
  var interval = setInterval(function () {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("count-down-" + itemID).innerHTML =
      days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
    if (distance < 0) {
      clearInterval(interval);
      document.getElementById("count-down-" + itemID).innerHTML = "EXPIRED";
    }
  }, 1000);
}

function searchOnPageByName() {
  let allCard = document.querySelectorAll(".task-card");
  let input = document.getElementById("searchbar").value;
  input = input.toLowerCase();
  let toSearch = document.querySelectorAll(".task-name");
  for (let i = 0; i < toSearch.length; i++) {
    if (!toSearch[i].innerHTML.toLocaleLowerCase().includes(input)) {
      allCard[i].style.display = "none";
    } else {
      allCard[i].style.display = "block";
    }
  }
}
