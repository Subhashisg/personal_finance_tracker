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
    // data
    var exp = [];
    var inc = [];
    var bud = {};

    // add expense
    function addExp() {
        var a = Number(eAmt.value);
        var c = eCat.value;
        var d = eDate.value;

        if (a == 0 || c == "" || d == "") {
            alert("Fill expense");
            return;
        }

        exp.push({ a, c, d });
        showExp();
        updBud();
        showHist();

        eAmt.value = "";
        eCat.value = "";
        eDate.value = "";
        eDesc.value = "";
    }

    // show expense
    function showExp() {
        eList.innerHTML = "";
        for (var i = 0; i < exp.length; i++) {
            var li = document.createElement("li");
            li.innerText = exp[i].d + " | " + exp[i].c + " | ₹" + exp[i].a;
            eList.appendChild(li);
        }
    }

    // add income
    function addInc() {
        var a = iAmt.value;
        var s = iSrc.value;
        var f = iFreq.value;

        if (a == "" || s == "" || f == "") {
            alert("Fill income");
            return;
        }

        inc.push({ a, s, f });
        showInc();
        showHist();

        iAmt.value = "";
        iSrc.value = "";
        iFreq.value = "";
    }

    // show income
    function showInc() {
        iList.innerHTML = "";
        for (var i = 0; i < inc.length; i++) {
            var li = document.createElement("li");
            li.innerText = inc[i].s + " | ₹" + inc[i].a + " | " + inc[i].f;
            iList.appendChild(li);
        }
    }

    // set budget
    function setBud() {
        var c = bCat.value;
        var a = Number(bAmt.value);

        if (c == "" || a == 0) {
            alert("Fill budget");
            return;
        }

        bud[c] = a;
        updBud();
    }

    // update budget
    function updBud() {
        bStatus.innerHTML = "";

        for (var c in bud) {
            var sp = 0;

            for (var i = 0; i < exp.length; i++) {
                if (exp[i].c == c) {
                    sp += exp[i].a;
                }
            }

            var per = Math.min((sp / bud[c]) * 100, 100);

            bStatus.innerHTML +=
                "<p>" + c + " : ₹" + sp + " / ₹" + bud[c] + "</p>" +
                "<div class='bar-box'><div class='bar' style='width:" + per + "%'></div></div>";
        }
    }

    // PHASE 5
    function showHist() {
        hList.innerHTML = "";
        var f = fType.value;

        if (f == "all" || f == "exp") {
            for (var i = 0; i < exp.length; i++) {
                var li = document.createElement("li");
                li.className = "exp";
                li.innerText = "Expense | " + exp[i].c + " | ₹" + exp[i].a;
                hList.appendChild(li);
            }
        }

        if (f == "all" || f == "inc") {
            for (var j = 0; j < inc.length; j++) {
                var li2 = document.createElement("li");
                li2.className = "inc";
                li2.innerText = "Income | " + inc[j].s + " | ₹" + inc[j].a;
                hList.appendChild(li2);
            }
        }
    }
</script>

</div>
</body>
</html>
