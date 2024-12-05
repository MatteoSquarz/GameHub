// -----------------------------------------------------------------
// Carousel scrolling buttons
// -----------------------------------------------------------------
let currentIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-item');
    const totalSlides = slides.length;

    if (index >= totalSlides) {
    currentIndex = 0;
    } else if (index < 0) {
    currentIndex = totalSlides - 1;
    } else {
    currentIndex = index;
    }

    const offset = -currentIndex * 100;
    document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
}

function nextSlide() {
    showSlide(currentIndex + 1);
}

function prevSlide() {
    showSlide(currentIndex - 1);
}

// -----------------------------------------------------------------
// Dynamically calculate header size and set content margin
// -----------------------------------------------------------------
function adjustContentMargin() {
    // Get the height of the header
    const header = document.querySelector('.fixed-header');
    const headerHeight = header.offsetHeight;

    // Adjust the margin of the content to match the header's height
    const content = document.querySelector('.carousel-container');
    content.style.marginTop = `${headerHeight}px`;
}

// Adjust on page load
window.addEventListener('load', adjustContentMargin);

// Adjust on window resize (in case the header size changes)
window.addEventListener('resize', adjustContentMargin);

// -----------------------------------------------------------------
// Comment section things (future)
// -----------------------------------------------------------------
/*
// Function to create a new comment
function createComment(text) {
  const commentList = document.getElementById('commentsList');

  // Create a new div for the comment item
  const commentItem = document.createElement('div');
  commentItem.classList.add('comment-item');

  // Add the comment text to the item
  commentItem.textContent = text;

  // Append the new comment item to the comment list
  commentList.appendChild(commentItem);
}

// Event listener for the "Submit" button
document.getElementById('submitComment').addEventListener('click', function() {
  const commentText = document.getElementById('commentText').value;

  if (commentText.trim()) {
    createComment(commentText); // Add the new comment
    document.getElementById('commentText').value = ''; // Clear the input field
  } else {
    alert('Please write a comment.');
  }
});

// Optional: Allow submitting with the Enter key
document.getElementById('commentText').addEventListener('keypress', function(event) {
  if (event.key === 'Enter') {
    event.preventDefault();  // Prevent newline in textarea
    document.getElementById('submitComment').click(); // Trigger submit
  }
});

*/