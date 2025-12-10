<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }
        
        .container-404 {
            text-align: center;
            padding: 2rem;
            max-width: 800px;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .error-number {
            font-size: 12rem;
            font-weight: 800;
            background: linear-gradient(45deg, #ff4444, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .error-number::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 8px;
            background: linear-gradient(45deg, #ff4444, #ff6b6b);
            bottom: 10px;
            left: 0;
            border-radius: 4px;
            opacity: 0.3;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }
        
        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(45deg, #4a6cf7, #6a82fb);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(74, 108, 247, 0.4);
        }
        
        .btn-secondary-custom {
            background: transparent;
            border: 2px solid #ddd;
            color: #666;
        }
        
        .btn-secondary-custom:hover {
            border-color: #4a6cf7;
            color: #4a6cf7;
            transform: translateY(-3px);
        }
        
        .search-box {
            max-width: 500px;
            margin: 0 auto 3rem;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: 2px solid #ddd;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.2);
        }
        
        .search-box button {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            padding: 0 1.5rem;
            border-radius: 50px;
            border: none;
            background: linear-gradient(45deg, #4a6cf7, #6a82fb);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .search-box button:hover {
            background: linear-gradient(45deg, #3a5ce5, #5a72e9);
        }
        
        .error-illustration {
            font-size: 8rem;
            color: #ff6b6b;
            margin-bottom: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .help-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .help-link {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .help-link:hover {
            color: #4a6cf7;
        }
        
        @media (max-width: 768px) {
            .error-number {
                font-size: 8rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-illustration {
                font-size: 6rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-custom {
                width: 100%;
                max-width: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .error-number {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 1.8rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-404">
        <div class="error-illustration">
            <i class="fas fa-map-signs"></i>
        </div>
        
        <h1 class="error-number">404</h1>
        <h2 class="error-title">Page Not Found</h2>
        
        <p class="error-message">
            Oops! The page you're looking for seems to have wandered off into the digital wilderness. 
            It might have been moved, deleted, or perhaps never existed.
        </p>
        
        
        <div class="action-buttons">
            <a href="{{ url('/dashboard') }}" class="btn btn-primary-custom btn-custom">
                <i class="fas fa-home me-2"></i> Go to Homepage
            </a>
            <button onclick="window.history.back()" class="btn btn-secondary-custom btn-custom">
                <i class="fas fa-arrow-left me-2"></i> Go Back
            </button>
        </div>
        
        
    </div>

    <!-- Bootstrap JS Bundle -->
   
</body>
</html>