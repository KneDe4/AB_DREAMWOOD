const soundList = [
  "mango/mango1.mp3",
  "mango/mango3.mp3",
  "mango/mango4.mp3",
  "mango/mango5.mp3"
];
const mangoAudio = document.getElementById('mango');
const mangoImg = document.getElementById("mangoid")
function changeRandomSound() {
  const randomIndex = Math.floor(Math.random() * soundList.length);
  const randomSound = soundList[randomIndex];
  mangoAudio.src = randomSound;
  mangoAudio.load();
}
function playmango() {
    changeRandomSound();
    mangoAudio.currentTime = 0
    mangoAudio.play()
}
