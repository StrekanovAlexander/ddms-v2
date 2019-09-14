;
(function() {

  document.addEventListener('DOMContentLoaded', function() {
    new SmartPhoto(".smart-photo", {
      nav: false,
      resizeStyle: 'fit',
    });
  });

  const alert = document.querySelector('.alert');
  if (alert) {
    alert.querySelector('.fas').addEventListener('click', function() {
      alert.style = 'display: none';
    })
  }

})();