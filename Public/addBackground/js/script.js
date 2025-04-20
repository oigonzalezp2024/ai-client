const form = document.getElementById('colorForm');
const colorBox = document.getElementById('colorBox');
const border = document.getElementById('border');
const border_radius = document.getElementById('border_radius');

form.addEventListener('input', () => {
  const bg = form.backgroundColor.value;
  const text = form.textColor.value;
  const border = form.border.value;
  const border_radius = form.border_radius.value;

  colorBox.style.backgroundColor = bg;
  colorBox.style.color = text;
  colorBox.style.borderRadius = border_radius+"px";
  colorBox.style.border = border+"px #ff0000 solid";
});
