<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Approval</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="bg-white shadow-lg rounded-lg p-8 max-w-md text-center">
    <h1 class="text-2xl font-bold text-gray-700">Account Pending Approval</h1>
    <p class="text-gray-500 mt-2">Your account is under review. We'll notify you once it's approved.</p>

    <div class="flex justify-center mt-6 space-x-2">
      <span class="w-3 h-3 bg-blue-500 rounded-full animate-bounce"></span>
      <span class="w-3 h-3 bg-blue-500 rounded-full animate-bounce delay-150"></span>
      <span class="w-3 h-3 bg-blue-500 rounded-full animate-bounce delay-300"></span>
    </div>

    <p class="text-gray-400 text-sm mt-4">Thank you for your patience!</p>
  </div>

  <style>
    .animate-bounce {
      animation: bounce 1.5s infinite;
    }

    .delay-150 {
      animation-delay: 0.2s;
    }

    .delay-300 {
      animation-delay: 0.4s;
    }

    @keyframes bounce {

      0%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-10px);
      }
    }
  </style>
</body>

</html>
