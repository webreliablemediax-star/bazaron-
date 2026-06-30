<!DOCTYPE html>
<html>
<head>
    <title>Seller Approved</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            height:100vh;
            overflow:hidden;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#0f172a,#1e293b,#312e81);
            position:relative;
        }

        .bg-circle{
            position:absolute;
            border-radius:50%;
            filter:blur(80px);
            opacity:.4;
        }

        .c1{
            width:350px;
            height:350px;
            background:#22c55e;
            top:-100px;
            left:-100px;
        }

        .c2{
            width:400px;
            height:400px;
            background:#6366f1;
            bottom:-150px;
            right:-100px;
        }

        .card{
            width:550px;
            padding:50px;
            border-radius:30px;
            background:rgba(255,255,255,.08);
            backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.15);
            text-align:center;
            color:#fff;
            box-shadow:0 25px 60px rgba(0,0,0,.3);
            position:relative;
            z-index:10;
        }

        .logo{
            font-size:14px;
            letter-spacing:3px;
            text-transform:uppercase;
            color:#a5b4fc;
            margin-bottom:25px;
        }

        .check-wrap{
            width:120px;
            height:120px;
            margin:auto;
            position:relative;
        }

        .pulse{
            position:absolute;
            inset:0;
            border-radius:50%;
            background:#22c55e;
            animation:pulse 2s infinite;
        }

        .check{
            position:absolute;
            inset:15px;
            background:#22c55e;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:50px;
            font-weight:bold;
            color:#fff;
        }

        @keyframes pulse{
            0%{
                transform:scale(1);
                opacity:.7;
            }
            100%{
                transform:scale(1.5);
                opacity:0;
            }
        }

        h1{
            margin-top:35px;
            font-size:32px;
            font-weight:700;
        }

        .sub{
            color:#cbd5e1;
            margin-top:15px;
            line-height:1.8;
            font-size:15px;
        }

        .status{
            margin-top:25px;
            display:inline-block;
            background:rgba(34,197,94,.15);
            color:#4ade80;
            padding:10px 18px;
            border-radius:50px;
            border:1px solid rgba(34,197,94,.3);
            font-size:14px;
            font-weight:600;
        }

        .progress{
            margin-top:35px;
            width:100%;
            height:10px;
            background:rgba(255,255,255,.1);
            border-radius:30px;
            overflow:hidden;
        }

        .bar{
            height:100%;
            width:0%;
            background:linear-gradient(90deg,#22c55e,#4ade80);
            animation:load 5s linear forwards;
        }

        @keyframes load{
            from{width:0%;}
            to{width:100%;}
        }

        .redirect{
            margin-top:15px;
            color:#94a3b8;
            font-size:14px;
        }

        .countdown{
            color:#4ade80;
            font-weight:700;
        }
    </style>
</head>

<body>

<div class="bg-circle c1"></div>
<div class="bg-circle c2"></div>

<div class="card">

    <div class="logo">
        BAZARON SELLER DESK
    </div>

    <div class="check-wrap">
        <div class="pulse"></div>
        <div class="check">✓</div>
    </div>

    <h1>Congratulations 🎉</h1>

    <p class="sub">
        Your seller account has been successfully verified and approved.
        You can now start product listing on Bazaron Marketplace.
    </p>

    <div class="status">
        ✔ Seller Account Approved
    </div>

    <div class="progress">
        <div class="bar"></div>
    </div>

    <div class="redirect">
        Redirecting in <span id="count" class="countdown">5</span> seconds...
    </div>

</div>

<script>

let sec = 5;

const timer = setInterval(() => {

    sec--;

    document.getElementById('count').innerText = sec;

    if(sec <= 0){
        clearInterval(timer);
    }

},1000);

setTimeout(function(){
    window.location.href = "{{ route('home') }}";
},5000);

</script>

</body>
</html>