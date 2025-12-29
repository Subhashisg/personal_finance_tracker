<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Personal Finance Tracker</title>

    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .welcome p {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        h1 {
            text-align: center;
            color: white;
            margin: 20px 0;
            font-size: 32px;
        }

        .box {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #667eea;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #5568d3;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }

        ul li {
            padding: 10px;
            margin-top: 8px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }

        .bar-box {
            background: #e9ecef;
            height: 20px;
            border-radius: 10px;
            margin-top: 8px;
            overflow: hidden;
        }

        .bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            width: 0%;
            border-radius: 10px;
            transition: width 0.3s;
        }

        .exp {
            color: #e74c3c;
            font-weight: bold;
        }

        .inc {
            color: #27ae60;
            font-weight: bold;
        }

        #bStatus p {
            margin: 15px 0 5px 0;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="welcome">
        <p>Welcome, <?php echo $username; ?>! <button class="logout-btn" onclick="location.href='logout.php'">Logout</button></p>
    </div>

    <h1>Personal Finance Tracker</h1>

<!-- EXPENSE -->
<div class="box">
    <h2>Expense</h2>

    <input type="number" id="eAmt" placeholder="Amount">

    <select id="eCat">
        <option value="">Category</option>
        <option>Food</option>
        <option>Transport</option>
        <option>Shopping</option>
        <option>Other</option>
    </select>

    <input type="date" id="eDate">
    <input type="text" id="eDesc" placeholder="Note (optional)">

    <button onclick="addExp()">Add Expense</button>

    <ul id="eList"></ul>
</div>

<!-- INCOME -->
<div class="box">
    <h2>Income</h2>

    <input type="number" id="iAmt" placeholder="Amount">
    <input type="text" id="iSrc" placeholder="Source">

    <select id="iFreq">
        <option value="">Frequency</option>
        <option>Monthly</option>
        <option>Weekly</option>
        <option>One-time</option>
    </select>

    <button onclick="addInc()">Add Income</button>

    <ul id="iList"></ul>
</div>

<!-- BUDGET -->
<div class="box">
    <h2>Budget</h2>

    <select id="bCat">
        <option value="">Category</option>
        <option>Food</option>
        <option>Transport</option>
        <option>Shopping</option>
        <option>Other</option>
    </select>

    <input type="number" id="bAmt" placeholder="Monthly budget">

    <button onclick="setBud()">Set Budget</button>

    <div id="bStatus"></div>
</div>

<!-- PHASE 5 : HISTORY -->
<div class="box">
    <h2>History</h2>

    <select id="fType" onchange="showHist()">
        <option value="all">All</option>
        <option value="exp">Expense</option>
        <option value="inc">Income</option>
    </select>

    <ul id="hList"></ul>
</div>

<script>
    function addExp() {
        var a = Number(eAmt.value);
        var c = eCat.value;
        var d = eDate.value;
        var desc = eDesc.value;

        if (a == 0 || c == "" || d == "") {
            alert("Fill all fields");
            return;
        }

        var form = new FormData();
        form.append('type', 'expense');
        form.append('category', c);
        form.append('amount', a);
        form.append('date', d);
        form.append('description', desc);

        fetch('add_transaction.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                eAmt.value = "";
                eCat.value = "";
                eDate.value = "";
                eDesc.value = "";
                loadData();
            } else {
                alert("Failed to add expense");
            }
        });
    }

    function addInc() {
        var a = Number(iAmt.value);
        var s = iSrc.value;
        var f = iFreq.value;

        if (a == 0 || s == "" || f == "") {
            alert("Fill all fields");
            return;
        }

        var form = new FormData();
        form.append('type', 'income');
        form.append('category', s);
        form.append('amount', a);
        form.append('date', new Date().toISOString().split('T')[0]);
        form.append('frequency', f);

        fetch('add_transaction.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                iAmt.value = "";
                iSrc.value = "";
                iFreq.value = "";
                loadData();
            } else {
                alert("Failed to add income");
            }
        });
    }

    function setBud() {
        var c = bCat.value;
        var a = Number(bAmt.value);

        if (c == "" || a == 0) {
            alert("Fill all fields");
            return;
        }

        var form = new FormData();
        form.append('category', c);
        form.append('amount', a);

        fetch('set_budget.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bCat.value = "";
                bAmt.value = "";
                loadBudgets();
            }
        });
    }

    function loadData() {
        fetch('get_transactions.php?type=' + fType.value)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTransactions(data.data);
            }
        });
        
        loadBudgets();
    }

    function displayTransactions(transactions) {
        hList.innerHTML = "";
        eList.innerHTML = "";
        iList.innerHTML = "";

        transactions.forEach(function(t) {
            var li = document.createElement("li");
            
            if (t.type == 'expense') {
                li.className = "exp";
                li.innerText = "Expense | " + t.date + " | " + t.category + " | ₹" + t.amount;
                hList.appendChild(li.cloneNode(true));
                eList.appendChild(li);
            } else {
                li.className = "inc";
                li.innerText = "Income | " + t.date + " | " + t.category + " | ₹" + t.amount;
                hList.appendChild(li.cloneNode(true));
                iList.appendChild(li);
            }
        });
    }

    function loadBudgets() {
        fetch('get_budgets.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bStatus.innerHTML = "";
                data.data.forEach(function(b) {
                    var per = Math.min((b.spent / b.budget) * 100, 100);
                    bStatus.innerHTML += 
                        "<p>" + b.category + " : ₹" + b.spent + " / ₹" + b.budget + "</p>" +
                        "<div class='bar-box'><div class='bar' style='width:" + per + "%'></div></div>";
                });
            }
        });
    }

    function showHist() {
        loadData();
    }

    loadData();
</script>

</div>
</body>
</html>
