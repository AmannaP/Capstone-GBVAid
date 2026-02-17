<div class="container mt-5">
    <h3 class="text-white mb-4">AI Interaction Logs (Safety Review)</h3>
    <table class="table table-dark table-hover border-purple">
        <thead>
            <tr>
                <th>Time</th>
                <th>User ID</th>
                <th>Message Snippet</th>
                <th>Risk Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch logs from DB
            $logs = $db->db_fetch_all("SELECT * FROM ai_logs ORDER BY interaction_date DESC");
            foreach($logs as $log): ?>
            <tr>
                <td><?= date('H:i', strtotime($log['interaction_date'])) ?></td>
                <td>User #<?= $log['user_id'] ?></td>
                <td><?= substr(htmlspecialchars($log['user_message']), 0, 50) ?>...</td>
                <td>
                    <span class="badge <?= $log['sentiment_flag'] == 'HIGH_RISK' ? 'bg-danger' : 'bg-success' ?>">
                        <?= $log['sentiment_flag'] ?>
                    </span>
                </td>
                <td><button class="btn btn-sm btn-outline-light" onclick="viewChat(<?= $log['log_id'] ?>)">View Full</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>