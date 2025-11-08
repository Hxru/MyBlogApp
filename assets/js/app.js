// app.js - Updated for traditional form submission
document.addEventListener('DOMContentLoaded', function() {
    const blogForm = document.getElementById('blogForm');
    const cancelBtn = document.getElementById('cancelBtn');
    const logoutBtn = document.getElementById('logoutBtn');
    const userInfo = document.getElementById('userInfo');

    // Check if user is logged in
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) {
        alert('Please login first!');
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 1500);
        return;
    }

    // Display username in header
    if (userInfo) {
        userInfo.innerHTML = `Welcome, <strong>${currentUser.username}</strong>`;
    }

    // Add basic validation before form submission
    if (blogForm) {
        blogForm.addEventListener('submit', function(e) {
            const title = document.getElementById('blogTitle').value.trim();
            const content = document.getElementById('blogContent').value.trim();

            // Basic validation
            if (!title) {
                e.preventDefault();
                alert('Please enter a blog title');
                return;
            }

            if (!content) {
                e.preventDefault();
                alert('Please enter blog content');
                return;
            }

            if (title.length < 5) {
                e.preventDefault();
                alert('Blog title should be at least 5 characters long');
                return;
            }

            if (content.length < 50) {
                e.preventDefault();
                alert('Blog content should be at least 50 characters long');
                return;
            }

            // If validation passes, the form will submit to pages/create-blog.php
            // Show loading state
            const submitBtn = blogForm.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Publishing...';
            submitBtn.disabled = true;
        });
    }

    // Handle cancel button
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel? Your changes will be lost.')) {
                window.location.href = 'blog-list.html';
            }
        });
    }

    // Handle logout
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('currentUser');
                alert('Logged out successfully!');
                window.location.href = 'login.html';
            }
        });
    }
});