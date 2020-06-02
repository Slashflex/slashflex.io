$(document).ready(() => {
  // Sets parallax effect only on root page
  if (window.location.pathname == '/') {
    // Parallax events
    (() => {
      // Add event listener
      document.addEventListener('mousemove', parallax);
      const chess1 = document.querySelector('.hero__chess1');
      const chess2 = document.querySelector('.hero__chess2');
      const chess3 = document.querySelector('.hero__chess3');

      // Magic happens here
      function parallax(e) {
        let width = window.innerWidth / 2;
        let height = window.innerHeight / 2;
        let mouseX = e.clientX;
        let mouseY = e.clientY;
        let depth = `${50 - (mouseX - width) * 0.01}% ${
          50 - (mouseY - height) * 0.01
        }%`;

        chess1.style.backgroundPosition = depth;
        chess2.style.backgroundPosition = depth;
        chess3.style.backgroundPosition = depth;
      }
    })();
  }

  // Swiper
  var swiper = new Swiper('.swiper-container', {
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      type: 'progressbar',
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

  // Disable right click on everything
  (() => {
    $(document).on('contextmenu', '*', () => {
      return false;
    });
  })();

  // Auto expand textarea as user input grows
  document.addEventListener(
    'input',
    (e) => {
      if (e.target.tagName.toLowerCase() !== 'textarea') return;
      autoExpand(e.target);
    },
    false
  );

  const autoExpand = (field) => {
    // Reset field height
    field.style.height = 'inherit';

    // Get the computed styles for the element
    const computed = window.getComputedStyle(field);

    // Calculate the height
    const height =
      parseInt(computed.getPropertyValue('border-top-width'), 10) +
      parseInt(computed.getPropertyValue('padding-top'), 10) +
      field.scrollHeight +
      parseInt(computed.getPropertyValue('padding-bottom'), 10) +
      parseInt(computed.getPropertyValue('border-bottom-width'), 10);

    field.style.height = `${height}px`;
  };

  // Full screen navigation toggle
  $('.toggle-menu').click(function () {
    $(this).toggleClass('active');
    $('#menu').toggleClass('open');
  });

  // Flash message
  setInterval(function() {
    $('.flash-notice').slideUp().fadeOut();
  }, 3000);
});

// Loader only on root page
if (window.location.pathname == '/') {
  var sentence = [
    '“Knowledge is power.” – <i style="color: #585858; font-size: 1.9rem">Francis Bacon</i>',
    '“Simplicity is the soul of efficiency.” – <i style="color: #585858; font-size: 1.9rem">Austin Freeman</i>',
    '“Make it work, make it right, make it fast.” – <i style="color: #585858; font-size: 1.9rem">Kent Beck</i>',
    '“Talk is cheap. Show me the code.“ – <i style="color: #585858; font-size: 1.9rem">Linus Torvalds</i>',
    '“Software is like sex: It’s better when it’s free.“ – <i style="color: #585858; font-size: 1.9rem">Linus Torvalds</i>',
    '“The Internet? Is that thing still around?” - <i style="color: #585858; font-size: 1.9rem">Homer Simpson</i>',
    '“I find your lack of faith disturbing.” - <i style="color: #585858; font-size: 1.9rem">Darth Vader</i>',
    '“Do. Or do not. There is no try.” - <i style="color: #585858; font-size: 1.9rem">Yoda</i>',
    '“We meet again at last.” - <i style="color: #585858; font-size: 1.9rem">Darth Vader</i>',
  ]; // Get a random index from the array sentence
  var randomNumber = Math.floor(Math.random() * sentence.length); // Get a random sentence from randomNumber
  
  var quote = sentence[randomNumber];
  $('body').append(
    '<div style="width: 100%; background: #27282c; color: #fff; margin-bottom: 40vh; height: 100%; position: fixed;" id="loading"><div class="loader"></div></div>'
  ); // Displays a random quote inside the loading div
  
  $('#loading').append(
    '<p class="text-center h1" style="color: #ffbe41; margin-bottom: 33rem;">'.concat(
      quote,
      '</p>'
    )
  );
  $(window).on('load', function () {
    setTimeout(removeLoader, 600); // Wait for page load
  });
  
  var removeLoader = function removeLoader() {
    $('#loading').fadeOut(500, function () {
      // FadeOut complete. Remove the loading div
      $(this).remove(); // Makes page more lightweight
    });
  };
}