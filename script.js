
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
