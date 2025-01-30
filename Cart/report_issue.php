<div class="report-issue-section">
    <h2>Report an Issue</h2>
    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<p style='color: green;'>Issue reported successfully!</p>";
    }
    ?>
    <p>If you encounter any issues while using our platform, please fill out the form below:</p>
    <form action="submit_issue.php" method="POST">
        <label for="issue-description">Describe the issue:</label><br>
        <textarea id="issue-description" name="issue_description" required></textarea><br><br>
        <input type="submit" value="Submit Issue">
    </form>
</div>