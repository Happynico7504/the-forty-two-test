document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.entry-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const participants = parseInt(form.participants.value, 10);
    const niceThings = parseInt(form.nice_things.value, 10);

    if (isNaN(participants) || participants < 1 || isNaN(niceThings) || niceThings < 1) {
      e.preventDefault();
      alert('Teilnehmer und schöne Dinge müssen mindestens 1 sein.');
    }
  });
});
