const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const menuLinks = sidebar.querySelectorAll('a');

// Toggle sidebar on hamburger click
hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');

    // Toggle visibility of the hamburger icon
    if (sidebar.classList.contains('active')) {
        hamburger.style.display = 'none'; // Hide hamburger when sidebar is active
    } else {
        hamburger.style.display = 'block'; // Show hamburger when sidebar is inactive
    }
});

// Close sidebar when clicking a menu item
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        sidebar.classList.remove('active');
        hamburger.style.display = 'block'; // Show hamburger again when sidebar closes
    });
});

// Close sidebar when clicking outside of it
document.addEventListener('click', (event) => {
    if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
        sidebar.classList.remove('active');
        hamburger.style.display = 'block'; // Show hamburger again when sidebar closes
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".count");
    const speed = 200; // Adjust speed as needed

    // Function to start counter animation
    const animateCounter = (counter) => {
        const target = +counter.getAttribute("data-target");
        const increment = target / speed;

        const updateCount = () => {
            const current = +counter.innerText;
            if (current < target) {
                counter.innerText = Math.ceil(current + increment);
                setTimeout(updateCount, 10); // Adjust delay for smoother or faster animation
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    };

    // Scroll observer
    const observerOptions = {
        threshold: 0.5, // Trigger when 50% of the element is visible
    };

    const observerCallback = (entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target); // Stop observing once the animation is done
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    counters.forEach((counter) => observer.observe(counter));
});

document.addEventListener('mousemove', (e) => {
    const eye = document.querySelector('.eye');
    const pupil = document.querySelector('.pupil');
    const rect = eye.getBoundingClientRect();
    const eyeX = rect.left + rect.width / 2;
    const eyeY = rect.top + rect.height / 2;
    const angle = Math.atan2(e.clientY - eyeY, e.clientX - eyeX);

    const offsetX = Math.cos(angle) * 15; // Adjust pupil movement distance
    const offsetY = Math.sin(angle) * 15;
    pupil.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
});


// Get the button
let backToTopBtn = document.getElementById("backToTopBtn");

// Show the button when the user scrolls down 20px from the top
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        backToTopBtn.style.display = "block";
    } else {
        backToTopBtn.style.display = "none";
    }
}

    // Chatbot toggle functionality
    const chatbotButton = document.getElementById('chatbotButton');
    const chatSidebar = document.getElementById('chatSidebar');
    const closeChat = document.getElementById('closeChat');

    chatbotButton.addEventListener('click', () => {
        chatSidebar.classList.toggle('active');
    });

    closeChat.addEventListener('click', () => {
        chatSidebar.classList.remove('active');
    });

// Scroll to the top of the document when the button is clicked
backToTopBtn.onclick = function() {
    window.scrollTo({top: 0, behavior: 'smooth'}); // Smooth scrolling
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default anchor behavior
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);

        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            // Update URL without adding to history
            history.replaceState(null, null, `#${targetId}`);
        }
    });
});