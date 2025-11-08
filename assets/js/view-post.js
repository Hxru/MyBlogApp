// view-post.js - Blog Post Detail Page
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    
    // Check authentication
    if (!currentUser) {
        alert('Please login first!');
        window.location.href = 'login.html';
        return;
    }
    
    // Display username
    const userInfo = document.getElementById('userInfo');
    if (userInfo) {
        userInfo.innerHTML = `Welcome, <strong>${currentUser.username}</strong>`;
    }
    
    // Load post details
    if (postId) {
        loadPostDetail(postId, currentUser);
    } else {
        document.getElementById('postTitle').textContent = 'Post Not Found';
        document.getElementById('postContent').textContent = 'The requested blog post could not be found.';
    }
    
    // Handle logout
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            localStorage.removeItem('currentUser');
            alert('Logged out successfully!');
            window.location.href = 'login.html';
        }
    });
});

async function loadPostDetail(postId, currentUser) {
    try {
        // For now, we'll get all posts and find the matching one
        // Later you can create a proper API endpoint for single posts
        const response = await fetch('api/get-posts.php');
        const data = await response.json();
        
        if (data.success && data.posts) {
            const post = data.posts.find(p => p.id == postId);
            
            if (post) {
                // Display post details
                document.getElementById('postTitle').textContent = post.title;
                document.getElementById('postDate').textContent = `📅 ${post.date}`;
                document.getElementById('postAuthor').textContent = `👤 By ${post.authorName}`;
                document.getElementById('postContent').textContent = post.content;
                
                // Show edit/delete buttons only for post author
                if (currentUser && post.authorId == currentUser.id) {
                    document.getElementById('editBtn').style.display = 'inline-block';
                    document.getElementById('deleteBtn').style.display = 'inline-block';
                    
                    // Add event listeners for edit and delete
                    document.getElementById('editBtn').addEventListener('click', function() {
                        alert('Edit functionality would open for post ' + postId);
                        // You can implement edit page later
                    });
                    
                    document.getElementById('deleteBtn').addEventListener('click', function() {
                        deletePost(postId);
                    });
                }
            } else {
                document.getElementById('postTitle').textContent = 'Post Not Found';
                document.getElementById('postContent').textContent = 'The requested blog post could not be found.';
            }
        } else {
            throw new Error('Failed to load post');
        }
    } catch (error) {
        console.error('Error loading post:', error);
        document.getElementById('postTitle').textContent = 'Error';
        document.getElementById('postContent').textContent = 'Failed to load the blog post. Please try again.';
    }
}

async function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        try {
            const response = await fetch('api/delete-post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ postId: postId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Post deleted successfully!');
                window.location.href = 'blog-list.html';
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error deleting post: ' + error.message);
        }
    }
}