// Main JavaScript file for E-Learning Platform

// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Confirm delete actions
function confirmDelete(message) {
    return confirm(message || 'B?n c? ch?c ch?n mu?n x?a?');
}

// Format time ago
function timeAgo(timestamp) {
    const seconds = Math.floor((new Date() - new Date(timestamp)) / 1000);
    
    let interval = seconds / 31536000;
    if (interval > 1) return Math.floor(interval) + " n?m tr??c";
    
    interval = seconds / 2592000;
    if (interval > 1) return Math.floor(interval) + " th?ng tr??c";
    
    interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + " ng?y tr??c";
    
    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + " gi? tr??c";
    
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + " ph?t tr??c";
    
    return "v?a xong";
}

// Preview image before upload
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Search functionality
function searchTable(inputId, tableId) {
    let input = document.getElementById(inputId);
    let filter = input.value.toUpperCase();
    let table = document.getElementById(tableId);
    let tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                let txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

// AJAX form submission
function submitAjaxForm(formId, successCallback) {
    let form = document.getElementById(formId);
    let formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (successCallback) successCallback(data);
        } else {
            alert(data.message || 'C? l?i x?y ra!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('C? l?i x?y ra!');
    });
}

// Load more functionality
let currentPage = 1;
function loadMore(url, containerId) {
    currentPage++;
    fetch(url + '?page=' + currentPage)
    .then(response => response.text())
    .then(data => {
        document.getElementById(containerId).innerHTML += data;
    })
    .catch(error => console.error('Error:', error));
}

// Quiz timer
function startQuizTimer(duration, displayId, formId) {
    let timer = duration * 60;
    let display = document.getElementById(displayId);
    
    let interval = setInterval(function() {
        let minutes = parseInt(timer / 60, 10);
        let seconds = parseInt(timer % 60, 10);
        
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        
        display.textContent = minutes + ":" + seconds;
        
        if (--timer < 0) {
            clearInterval(interval);
            document.getElementById(formId).submit();
        }
    }, 1000);
}

// Real-time search
function liveSearch(query, targetUrl, resultsId) {
    if (query.length < 2) {
        document.getElementById(resultsId).innerHTML = '';
        return;
    }
    
    fetch(targetUrl + '?q=' + encodeURIComponent(query))
    .then(response => response.text())
    .then(data => {
        document.getElementById(resultsId).innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
}
