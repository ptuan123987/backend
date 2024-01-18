<!-- resources/views/users/choose_marital_status.blade.php -->

<html>
<head>
    <title>Choose Marital Status</title>
</head>
<body>
    <h1>Choose Marital Status</h1>

    <button onclick="updateMaritalStatus('single')">Single</button>
    <button onclick="showMarriedInput()">Married</button>

    <div id="marriedInput" style="display: none;">
        <label for="spouseName">Spouse Name:</label>
        <input type="text" id="spouseName" />
        <button onclick="saveMarriedStatus()">Save</button>
    </div>

    <script>
        function updateMaritalStatus(status) {
            localStorage.setItem('marital_status', status);
            alert('Marital status updated successfully.');
            navigateToNextPage(); // Chuyển trang sau khi cập nhật thành công
        }

        function showMarriedInput() {
            document.getElementById('marriedInput').style.display = 'block';
        }

        function saveMarriedStatus() {
            var spouseName = document.getElementById('spouseName').value;
            localStorage.setItem('marital_status', 'married');
            localStorage.setItem('spouse_name', spouseName);
            alert('Marital status updated successfully. Spouse Name: ' + spouseName);
            navigateToNextPage(); // Chuyển trang sau khi cập nhật thành công
        }

        function navigateToNextPage() {
            window.location.href = '/ngay-thanh-nam-sinh';
        }
    </script>
</body>
</html>
