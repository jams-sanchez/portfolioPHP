const clickBox = document.getElementsByClassName("click-box");
console.log(clickBox);

for (let box of clickBox) {
  box.addEventListener("click", (e) => {
    console.log(box.innerText);
    if (box.innerText === "Portfolio") {
      window.open("https://james-sanchez.students-laplateforme.io/");
    } else if (box.innerText === "LinkedIn") {
      window.open("https://www.linkedin.com/in/james-sanchez-3a8a80332/");
    } else if (box.innerText === "Github") {
      window.open("https://github.com/jams-sanchez");
    } else {
      window.open("../links-tree/CV-SANCHEZJames.pdf");
    }
  });
}
