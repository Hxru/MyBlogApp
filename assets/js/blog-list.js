// Get current user
function getCurrentUser() {
    return JSON.parse(localStorage.getItem('currentUser'));
}

// Check if user is logged in
function checkAuth() {
    const currentUser = getCurrentUser();
    if (!currentUser) {
        alert('Please login first!');
        window.location.href = 'login.html';
        return false;
    }
    return currentUser;
}

// Display all blog posts FROM DATABASE
async function displayBlogPosts() {
    const blogListContainer = document.getElementById('blogList');
    const currentUser = getCurrentUser();
    
    try {
        // Fetch posts from API
        const response = await fetch('api/get-posts.php');
        const data = await response.json();
        
        console.log('API Response:', data);
        
        if (!data.success || !data.posts || data.posts.length === 0) {
            blogListContainer.innerHTML = `
                <div class="no-posts">
                    <h3>No blog posts yet</h3>
                    <p>Create your first blog post to get started!</p>
                    <a href="index.html" class="btn btn-primary">Create First Post</a>
                </div>
            `;
            return;
        }
        
        const posts = data.posts;
        
        blogListContainer.innerHTML = posts.map(post => `
            <article class="blog-post">
                <h3>${post.title}</h3>
                <div class="post-meta">
                    <span class="post-date">📅 ${post.date}</span>
                    ${post.authorName ? `<span class="post-author">👤 By ${post.authorName}</span>` : ''}
                </div>
                <div class="post-excerpt">
                    <p>${post.excerpt || post.content.substring(0, 100) + '...'}</p>
                </div>
                <div class="post-actions">
                    <button onclick="viewPost(${post.id})" class="btn btn-primary">Read More</button>
                    ${currentUser && post.authorId == currentUser.id ? 
                        `<button onclick="editPost(${post.id})" class="btn btn-secondary">Edit</button>
                         <button onclick="deletePost(${post.id})" class="btn btn-danger">Delete</button>` : 
                        ''}
                </div>
            </article>
        `).join('');
        
    } catch (error) {
        console.error('Error loading posts:', error);
        blogListContainer.innerHTML = `
            <div class="no-posts">
                <h3>Error loading posts</h3>
                <p>Please try again later.</p>
            </div>
        `;
    }
}

// View individual post - FIXED: Remove quotes around postId
function viewPost(postId) {
    console.log('Viewing post:', postId);
    // Show full post in an alert for now
    fetch('api/get-posts.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const post = data.posts.find(p => p.id == postId);
                if (post) {
                    alert(`📝 ${post.title}\n\n👤 By ${post.authorName}\n📅 ${post.date}\n\n${post.content}`);
                }
            }
        })
        .catch(error => {
            alert('Error loading post details');
        });
}

// Edit post
function editPost(postId) {
    alert('Edit post: ' + postId + '\n\nThis would open an edit form. To implement:\n1. Create edit-post.html\n2. Load post data into form\n3. Update via API');
    // You can implement this later
}

// Delete post
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
                displayBlogPosts(); // Refresh the list
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error deleting post: ' + error.message);
        }
    }
}

// Logout functionality
document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('currentUser');
        alert('Logged out successfully!');
        window.location.href = 'login.html';
    }
});

// Update header to show username
function updateHeader() {
    const currentUser = getCurrentUser();
    const header = document.querySelector('.app-header');
    
    if (currentUser && header) {
        if (!header.querySelector('.user-info')) {
            const userInfo = document.createElement('div');
            userInfo.className = 'user-info';
            userInfo.innerHTML = `<span>Welcome, ${currentUser.username}</span>`;
            header.appendChild(userInfo);
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    const currentUser = checkAuth();
    if (currentUser) {
        console.log('User is logged in:', currentUser.username);
        updateHeader();
        displayBlogPosts();
    }
});