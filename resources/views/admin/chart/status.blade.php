<canvas id="doughnut" width="200" height="200"></canvas>
<script>
    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $status['1'] }},
                    {{ $status['0'] }}
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)'
                ]
            }],
            labels: [
                'Active',
                'Inactive'
            ]
        },
        options: {
            maintainAspectRatio: false
        }
    };

    var ctx = document.getElementById('doughnut').getContext('2d');
    new Chart(ctx, config);
</script>
