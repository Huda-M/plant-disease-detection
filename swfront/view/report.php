<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="../style/report.css" rel="stylesheet" />
</head>
<body>
    <div class="parent">
        <div class="report-container">
            <h2>Report Post</h2>
            <form onsubmit="alert('Report submitted!'); return false;">
              <label for="reason">Reason for reporting:</label>
              <textarea id="reason" name="reason" placeholder="Type your reason here..." required></textarea>
              <button type="submit" class="btn">Submit Report</button>
            </form>
            <a href="index.php" class="back-link">&larr; Back to Home</a>
        </div>

    </div>
</body>
</html>