<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "controllerUserData.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaf's Kitchen - Login & Registration</title>
    <link rel="icon" type="image/png" href="logo/logo.png">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Kalam:wght@300;400;700&family=Caveat:wght@400;500;600;700&family=Cinzel:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&display=swap');

@font-face {
    font-family: 'LHF Ascribe';
    src: url('fonts/CR_LHF-Ascribe-Regular-Regular-otf-400.otf') format('opentype'),
         url('fonts/LHFAscribe.woff2') format('woff2'),
         url('fonts/LHFAscribe.woff') format('woff'),
         url('fonts/LHFAscribe.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-image: url("authbackground/authbackground.jpg");
  background-color: black;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
  padding: 0;
  position: relative;
  z-index: 0;
  overflow: hidden;
}

body::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: -1; 
  pointer-events: none; 
}

/* Hide navbar on mobile */
.navbar {
    display: none;
}

/* Mobile First - Fullscreen Container */
.container {
    position: relative;
    width: 100%;
    height: 100vh;
    background: #ffffff;
    border-radius: 0;
    box-shadow: none;
    overflow: hidden;
    margin: 0;
}

.form-box{
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 60%;
    background: #fff;
    display: flex;
    align-items: center;
    color: #333;
    text-align: center;
    padding: 20px 25px;
    z-index: 1;
    transition: .6s ease-in-out 0.8s, visibility 0s 1s;
    overflow: hidden;
}

.container.active .form-box{
    bottom: 40%;
}

.container.forgot-active .form-box{
    bottom: 40%;
}

.container.otp-active .form-box{
    bottom: 40%;
}

.form-box.signup{
    visibility: hidden;
}

.form-box.forgot{
    visibility: hidden;
}

.form-box.otp{
    visibility: hidden;
}

.container.active .form-box.signup{
    visibility: visible;
}

.container.forgot-active .form-box.forgot{
    visibility: visible;
}

.container.otp-active .form-box.otp{
    visibility: visible;
}

form{
    width: 100%;
}

.container h1{
    font-size: 28px;
    margin-bottom: 8px;
}

.container h4{
    font-size: 12px;
    font-weight: 400;
    margin: 6px 0 15px 0;
    text-align: center;
    line-height: 1.3;
}

.input-box {
    position: relative;
    margin: 15px 0;
} 

.input-box input{
    width: 100%;
    padding: 12px 40px 12px 15px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

.input-box input::placeholder{
    color: #888;
    font-weight: 400;
}

.input-box i{
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #888;
}

.otp-container {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 15px 0;
}

.otp-input {
    width: 42px;
    height: 42px;
    border: 2px solid #ddd;
    border-radius: 8px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    background: #f9f9f9;
    outline: none;
    transition: all 0.3s ease;
}

.otp-instruction {
    font-size: 13px;
    color: #555;
    font-weight: normal;
    line-height: 1.4;
}

.otp-input:focus {
    border-color: #DC2626;
    background: #fff;
    box-shadow: 0 0 5px rgba(220, 38, 38, 0.3);
}

.otp-input.filled {
    background: #DC2626;
    color: white;
    border-color: #DC2626;
}

.otp-timer {
    font-size: 13px;
    color: #666;
    margin: 8px 0;
}

.resend-link {
    color: #DC2626;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
}

.resend-link:hover {
    color: #B91C1C;
    text-decoration: underline;
}

.resend-link.disabled {
    color: #ccc;
    cursor: not-allowed;
    text-decoration: none;
}

.forgot-link{
    margin: -8px 0 15px;
}

.forgot-link a{
    font-size: 13px;
    color: #333;
    text-decoration: none;
    cursor: pointer;
}

.forgot-link a:hover{
    color: #DC2626;
}

.btn{
    width: 100%;
    height: 42px;
    background: #DC2626;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .1);
    border: none;
    cursor: pointer;
    font-size: 15px;
    color: #fff;
    font-weight: 600;
}

.btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.container p{
    font-size: 13px;
    margin: 12px 0;
}

.social-icons{
    font-size: 13px;
    margin: 12px 0;
}

.social-icons a{
    display: inline-flex;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 8px;
    font-size: 22px;
    color: #333;
    text-decoration: none;
    margin: 0 8px;    
}

.toggle-box{
    position: absolute;
    width: 100%;
    height: 100%;
}

.toggle-box::before {
    content: '';
    position: absolute;
    left: 0;
    top: -1880px;
    width: 100%;
    height: 2200px;
    border-radius: 20vw;
    background: linear-gradient(#DC2626, #991B1B);
    z-index: 2;
    transition: 1.4s ease-in-out;
}

.container.active .toggle-box::before,
.container.forgot-active .toggle-box::before,
.container.otp-active .toggle-box::before {
    top: 60%;
    left: 0;
}

.toggle-panel{
    position: absolute;
    width: 100%;
    height: 40%;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 2;
    transition: .6s ease-in-out;
    padding: 0 20px;
}

.toggle-panel h1 {
    font-size: 34px;
    margin-bottom: 8px;
}

.toggle-panel h4 {
    font-size: 12px;
    line-height: 1.4;
    margin-bottom: 10px;
}

.toggle-panel.toggle-left {
    top: 0;
    left: 0;
    transition-delay: .6s;
}

.container.active .toggle-panel.toggle-left,
.container.forgot-active .toggle-panel.toggle-left,
.container.otp-active .toggle-panel.toggle-left{
    left: 0;
    top: -40%;
    transition-delay: .6s;
}

.toggle-panel.toggle-right{
    right: 0;
    bottom: -40%;
    transition-delay: .6s;
}

.container.active .toggle-panel.toggle-right,
.container.forgot-active .toggle-panel.toggle-right,
.container.otp-active .toggle-panel.toggle-right{
    bottom: 40px;
    right: 0;
    transition-delay: .6s;
}

.toggle-panel p{
    margin-bottom: 15px;
    font-size: 13px;
}

.toggle-panel .btn{
    width: 200px;
    height: 44px;
    background: transparent;
    border: 2px solid #fff;
    box-shadow: none;
    font-size: 14px;
}

.back-btn {
    background: none;
    border: none;
    color: #DC2626;
    font-size: 13px;
    cursor: pointer;
    margin: 8px 0;
    text-decoration: underline;
}

.back-btn:hover {
    color: #B91C1C;
}

/* Success and Error Messages */
.success-message {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 8px;
    margin: 10px 0;
    border: 1px solid #c3e6cb;
    display: none;
    font-size: 13px;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 8px;
    margin: 10px 0;
    border: 1px solid #f5c6cb;
    display: none;
    font-size: 13px;
}
/* NEW ERROR MODAL - With Logo & Notch */
.error-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.error-modal-overlay.show {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.error-modal-card {
    background: white;
    border-radius: 16px;
    width: 70%;
    max-width: 400px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    overflow: hidden;
    position: relative;
}

@keyframes slideDown {
    from {
        transform: translateY(-100px) scale(0.8);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.error-modal-header {
    position: relative;
    height: 80px;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
}

.red-bar-left {
    position: absolute;
    top: 0;
    left: 0;
    width: 35%;
    height: 100%;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-radius: 16px 0 0 0;
}

.red-bar-right {
    position: absolute;
    top: 0;
    right: 0;
    width: 35%;
    height: 100%;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-radius: 0 16px 0 0;
}

.center-notch {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 50px;
    background: white;
    border-radius: 0 0 50px 50px;
}

.logo-circle {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 50px;
    background: white;
    border: 3px solid #DC2626;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    overflow: hidden;
}

.logo-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.error-modal-body {
    padding: 40px 30px 35px;
    background: white;
}

.error-title {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
    text-align: center;
}

.error-message-text {
    font-size: 15px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 25px;
    text-align: center;
}

.try-again-btn {
    width: 100%;
    padding: 7px;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.try-again-btn:hover {
    transform: translateY(-0px);
    box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
}

.try-again-btn:active {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 480px) {
    .error-modal-card {
        max-width: 350px;
    }
    
    .error-modal-header {
        height: 70px;
    }
    
    .red-bar-left,
    .red-bar-right {
        width: 37%;
    }
    
    .center-notch {
        width: 90px;
        height: 45px;
    }
    
    .logo-circle {
        top: 12px;
        width: 45px;
        height: 45px;
        border-width: 2px;
    }
    
    .error-modal-body {
        padding: 35px 20px 30px;
    }
    
    .error-title {
        font-size: 15px;
    }
    
    .error-message-text {
        font-size: 11px;
    }
    
    .try-again-btn {
        padding: 8px;
        font-size: 11px;
    }
}

/* Loading Screen */
#loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.loading-container {
    text-align: center;
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
    animation: slideInBounce 0.6s ease-out;
    min-width: 280px;
}

@keyframes slideInBounce {
    0% {
        opacity: 0;
        transform: translateY(-100px) scale(0.8);
    }
    60% {
        opacity: 1;
        transform: translateY(10px) scale(1.05);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.spinner {
    width: 70px;
    height: 70px;
    margin: 0 auto 25px;
    border: 5px solid #f0f0f0;
    border-top: 5px solid #DC2626;
    border-radius: 50%;
    animation: spin 1.2s linear infinite;
    position: relative;
}

.spinner::before {
    content: '';
    position: absolute;
    top: 5px;
    left: 5px;
    right: 5px;
    bottom: 5px;
    border-radius: 50%;
    border: 2px solid transparent;
    border-top-color: #DC2626;
    animation: spin 2s linear infinite reverse;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-family: 'Arial', sans-serif;
    font-size: 17px;
    color: #333;
    font-weight: 600;
    margin-bottom: 18px;
    animation: loadingPulse 2s ease-in-out infinite;
}

@keyframes loadingPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.loading-dots {
    display: flex;
    justify-content: center;
    gap: 7px;
}

.loading-dots span {
    width: 10px;
    height: 10px;
    background: #DC2626;
    border-radius: 50%;
    animation: dotBounce 1.4s ease-in-out infinite both;
}

.loading-dots span:nth-child(1) { animation-delay: -0.32s; }
.loading-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes dotBounce {
    0%, 80%, 100% {
        transform: scale(0);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

.otp-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.otp-modal-overlay.show {
    display: flex;
}

.otp-modal-card {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 450px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    overflow: hidden;
    position: relative;
}

.otp-modal-header {
    position: relative;
    height: 80px;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
}

.otp-red-bar-left {
    position: absolute;
    top: 0;
    left: 0;
    width: 35%;
    height: 100%;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-radius: 16px 0 0 0;
}

.otp-red-bar-right {
    position: absolute;
    top: 0;
    right: 0;
    width: 35%;
    height: 100%;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-radius: 0 16px 0 0;
}

.otp-center-notch {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 50px;
    background: white;
    border-radius: 0 0 50px 50px;
}

.otp-logo-circle {
    position: absolute;
    top: 15px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 50px;
    background: white;
    border: 3px solid #DC2626;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    overflow: hidden;
}

.otp-logo-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.otp-modal-body {
    padding: 40px 30px 35px;
    background: white;
}

.otp-modal-title {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
    text-align: center;
}

.otp-instruction {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 20px;
    text-align: center;
}

.otp-instruction span {
    color: #DC2626;
    font-weight: 600;
}

.otp-container {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 20px 0;
}

.otp-input {
    width: 42px;
    height: 42px;
    border: 2px solid #ddd;
    border-radius: 8px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    background: #f9f9f9;
    outline: none;
    transition: all 0.3s ease;
}

.otp-input:focus {
    border-color: #DC2626;
    background: #fff;
    box-shadow: 0 0 5px rgba(220, 38, 38, 0.3);
}

.otp-input.filled {
    background: #DC2626;
    color: white;
    border-color: #DC2626;
}

.otp-timer {
    font-size: 13px;
    color: #666;
    margin: 12px 0;
    text-align: center;
}

.otp-verify-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 15px;
}

.otp-verify-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
}

.otp-verify-btn:active {
    transform: translateY(0);
}

.otp-verify-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.resend-link {
    color: #DC2626;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    display: block;
    text-align: center;
    margin-top: 12px;
    font-weight: 500;
}

.resend-link:hover {
    color: #B91C1C;
    text-decoration: underline;
}

.resend-link.disabled {
    color: #ccc;
    cursor: not-allowed;
    text-decoration: none;
}

.otp-error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 8px;
    margin: 12px 0;
    border: 1px solid #f5c6cb;
    font-size: 13px;
    text-align: center;
}

.otp-success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 8px;
    margin: 12px 0;
    border: 1px solid #c3e6cb;
    font-size: 13px;
    text-align: center;
}

/* Mobile Responsive */
@media (max-width: 480px) {
    .otp-modal-card {
        max-width: 350px;
    }
    
    .otp-modal-header {
        height: 70px;
    }
    
    .otp-red-bar-left,
    .otp-red-bar-right {
        width: 37%;
    }
    
    .otp-center-notch {
        width: 90px;
        height: 45px;
    }
    
    .otp-logo-circle {
        top: 12px;
        width: 45px;
        height: 45px;
        border-width: 2px;
    }
    
    .otp-modal-body {
        padding: 35px 20px 30px;
    }
    
    .otp-modal-title {
        font-size: 18px;
    }
    
    .otp-instruction {
        font-size: 12px;
    }
    
    .otp-input {
        width: 38px;
        height: 38px;
        font-size: 15px;
    }
    
    .otp-verify-btn {
        padding: 10px;
        font-size: 14px;
    }
}

/* Custom Modal */
.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.custom-modal.fade-in {
    opacity: 1;
}

.custom-modal-content {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    animation: modalSlideIn 0.5s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.custom-modal-header h5 {
    margin: 0;
    font-size: 18px;
    color: #333;
    font-weight: bold;
}

.close-btn {
    font-size: 22px;
    color: #aaa;
    cursor: pointer;
    transition: color 0.3s;
}

.close-btn:hover {
    color: #333;
}

.custom-modal-body {
    font-size: 14px;
    color: #555;
    margin-top: 15px;
    line-height: 1.5;
}

.custom-modal-footer {
    margin-top: 25px;
}

.custom-btn {
    padding: 10px 25px;
    font-size: 16px;
    color: white;
    background-color: #4CAF50;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.custom-btn:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

.custom-btn:focus {
    outline: none;
}

/* Small mobile adjustments */
@media screen and (max-width: 380px) {
    .container h1 {
        font-size: 26px;
    }
    
    .input-box {
        margin: 12px 0;
    }
    
    .input-box input {
        padding: 11px 38px 11px 14px;
        font-size: 13px;
    }
    
    .btn {
        height: 40px;
        font-size: 14px;
    }
    
    .social-icons a {
        padding: 9px;
        font-size: 20px;
        margin: 0 6px;
    }
    
    .toggle-panel h1 {
        font-size: 22px;
    }
    
    .toggle-panel .btn {
        width: 150px;
        height: 38px;
        font-size: 13px;
    }
}

/* Desktop styles - Show navbar */
@media screen and (min-width: 769px) {
    body {
        padding: 20px;
        padding-top: 80px;
    }

    .navbar {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 20px 50px;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .logo {
        display: flex;
        align-items: center;
        font-size: 16px;
        font-weight: 800;
        color: #DC2626;
        text-decoration: none;
        transition: all 0.3s ease;
        font-family: 'LHF Ascribe', 'Cinzel', 'Times New Roman', serif;
        letter-spacing: 2px;
        text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        font-style: normal;
        transform: skewX(-5deg);
    }

    .logo .logo-z {
        font-weight: 900;
        font-size: 25px;
        text-shadow: 4px 4px 8px rgba(0,0,0,0.7);
        font-family: 'LHF Ascribe', 'Cinzel', 'Times New Roman', serif;
    }

    .logo:hover {
        transform: scale(1.05) skewX(-5deg);
        color: #B91C1C;
    }

    .logo-icon {
        width: 42px;
        height: 42px;
        margin-right: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .logo:hover .logo-icon {
        transform: rotate(10deg);
    }

    .logo-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: transparent;
        mix-blend-mode: screen;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 30px;
    }

    .nav-menu li a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        font-size: 16px;
        position: relative;
        transition: color 0.3s ease;
    }

    .nav-menu li a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background: #DC2626;
        transition: width 0.3s ease;
    }

    .nav-menu li a:hover {
        color: #DC2626;
    }

    .nav-menu li a:hover::after {
        width: 100%;
    }

    .container {
        width: 765px;
        height: 495px;
        border-radius: 30px;
        box-shadow: 0 0 30px rgba(0, 0, 0, .2);
    }

    .form-box{
        right: 0;
        width: 50%;
        height: 100%;
        padding: 36px;
        bottom: auto;
        overflow: hidden;
    }
    
    .container h1 {
        font-size: 36px;
        margin-bottom: 10px;
    }
    
    .container h4 {
        font-size: 13px;
        margin: 8px 0 20px 0;
    }
    
    .input-box {
        margin: 22px 0;
    }
    
    .input-box input {
        padding: 14px 45px 14px 18px;
        font-size: 15px;
    }
    
    .input-box i {
        right: 20px;
        font-size: 20px;
    }
    
    .forgot-link {
        margin: -10px 0 18px;
    }
    
    .forgot-link a {
        font-size: 14.5px;
    }
    
    .btn {
        height: 45px;
        font-size: 17px;
    }
    
    .container p {
        font-size: 15px;
        margin: 18px 0;
    }
    
    .social-icons {
        font-size: 14.5px;
        margin: 18px 0;
    }
    
    .social-icons a {
        padding: 12px;
        font-size: 26px;
        margin: 0 10px;
    }

    .container.active .form-box,
    .container.forgot-active .form-box,
    .container.otp-active .form-box{
        right: 50%;
        bottom: 0;
    }

    .toggle-box::before {
        left: -250%;
        top: 0;
        width: 300%;
        height: 100%;
        border-radius: 150px;
    }

    .container.active .toggle-box::before,
    .container.forgot-active .toggle-box::before,
    .container.otp-active .toggle-box::before {
        left: 50%;
        top: 0;
    }

    .toggle-panel{
        width: 50%;
        height: 100%;
        padding: 0 30px;
    }
    
    .toggle-panel h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    .toggle-panel h4 {
        font-size: 14px;
        margin-bottom: 12px;
    }
    
    .toggle-panel p {
        font-size: 15px;
        margin-bottom: 20px;
    }
    
    .toggle-panel .btn {
        width: 180px;
        height: 45px;
        font-size: 16px;
    }

    .toggle-panel.toggle-left {
        left: 0;
        top: 0;
    }

    .container.active .toggle-panel.toggle-left,
    .container.forgot-active .toggle-panel.toggle-left,
    .container.otp-active .toggle-panel.toggle-left{
        left: -50%;
        top: 0;
    }

    .toggle-panel.toggle-right{
        right: -50%;
        bottom: 0;
    }

    .container.active .toggle-panel.toggle-right,
    .container.forgot-active .toggle-panel.toggle-right,
    .container.otp-active .toggle-panel.toggle-right{
        right: 0;
        bottom: 0;
    }
    
    .otp-container {
        gap: 10px;
        margin: 20px 0;
    }
    
    .otp-input {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .otp-instruction {
        font-size: 14px;
    }
    
    .otp-timer {
        font-size: 14px;
        margin: 10px 0;
    }
    
    .resend-link {
        font-size: 14px;
    }
    
    .otp-verify-btn {
        height: 45px;
        font-size: 16px;
        margin: 20px 0 15px 0;
    }
    
    .otp-modal-content h2 {
        font-size: 28px;
        margin-bottom: 15px;
    }
    
    .otp-modal-content h2::before {
        font-size: 32px;
    }
    
    .otp-modal-content {
        padding: 40px 35px 35px;
    }
    
    .custom-modal-content {
        padding: 30px;
        max-width: 450px;
    }
    
    .custom-modal-header h5 {
        font-size: 20px;
    }
    
    .custom-modal-header {
        padding-bottom: 20px;
    }
    
    .custom-modal-body {
        font-size: 16px;
        margin-top: 20px;
    }
    
    .custom-modal-footer {
        margin-top: 30px;
    }
    
    .custom-btn {
        padding: 12px 30px;
        font-size: 18px;
    }
    
    .close-btn {
        font-size: 24px;
    }
    
    .loading-container {
        padding: 50px 40px;
        min-width: 300px;
    }
    
    .spinner {
        width: 80px;
        height: 80px;
        margin: 0 auto 30px;
        border: 6px solid #f0f0f0;
        border-top: 6px solid #DC2626;
    }
    
    .spinner::before {
        border: 2px solid transparent;
    }
    
    .loading-text {
        font-size: 20px;
        margin-bottom: 20px;
    }
    
    .loading-dots {
        gap: 8px;
    }
    
    .loading-dots span {
        width: 12px;
        height: 12px;
    }
}

/* Mobile responsive adjustments */
@media screen and (max-width: 480px) {
    .modal-error-content,
    .custom-modal-content {
        width: 95%;
        padding: 30px 25px 25px;
        border-radius: 16px;
        max-width: none;
        margin: 20px;
    }
    
    .modal-error-content h3 {
        font-size: 20px;
        margin-bottom: 18px;
    }
    
    .modal-error-content ul li {
        font-size: 14px;
        padding: 6px 0 6px 40px;
        padding-right: 12px;
    }
    
    .close-error-btn {
        width: 30px;
        height: 30px;
        top: 12px;
        right: 15px;
        font-size: 16px;
    }

    .otp-modal-content {
        padding: 30px 20px 25px;
        border-radius: 12px;
    }
    
    .otp-modal-content h2 {
        font-size: 22px;
        margin-bottom: 10px;
    }
    
    .otp-instruction {
        font-size: 13px;
        margin-bottom: 18px;
    }
    
    .otp-container {
        gap: 6px;
        margin: 18px 0;
    }
    
    .otp-input {
        width: 40px;
        height: 40px;
        font-size: 16px;
        border-radius: 8px;
    }
    
    .otp-verify-btn {
        height: 40px;
        font-size: 14px;
        border-radius: 10px;
    }
    
    .otp-timer, .resend-link {
        font-size: 13px;
    }
    
    .otp-error, .otp-success {
        font-size: 13px;
        padding: 10px;
    }
}

@media screen and (max-width: 360px) {
    .otp-container {
        gap: 4px;
    }
    
    .otp-input {
        width: 35px;
        height: 35px;
        font-size: 14px;
        border-radius: 6px;
    }
}
</style>

<body>
  <!-- Navigation Bar (Hidden on mobile, shown on desktop) -->
    <nav class="navbar">
        <a href="#" class="logo">
            <div class="logo-icon">
                <img src="logo/logo.png" alt="Zaf's Kitchen Logo">
            </div>
            <span class="logo-z">Z</span>af's Kitchen
        </a>
        <ul class="nav-menu">
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        </ul>
    </nav>

<!-- Error Modal - New Design -->
<?php if(count($errors) > 0): ?>
    <div class="error-modal-overlay show" id="errorModal">
        <div class="error-modal-card">
            <div class="error-modal-header">
                <div class="red-bar-left"></div>
                <div class="red-bar-right"></div>
                <div class="center-notch"></div>
                
                <div class="logo-circle">
                    <img src="logo/logo.png" alt="Logo">
                </div>
            </div>
            
            <div class="error-modal-body">
                <h3 class="error-title">Oops! Something went wrong</h3>
                <p class="error-message-text">
                    <?php echo htmlspecialchars($errors[array_key_first($errors)]); ?>
                </p>
                <button class="try-again-btn" onclick="closeErrorModal()">
                    Try Again
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

        <!-- Reset Success Modal - New Design -->
        <?php if (isset($_SESSION['reset_success'])): ?>
        <div class="error-modal-overlay show" id="resetSuccessModal">
            <div class="error-modal-card">
                <div class="error-modal-header">
                    <div class="red-bar-left"></div>
                    <div class="red-bar-right"></div>
                    <div class="center-notch"></div>
                    
                    <div class="logo-circle">
                        <img src="logo/logo.png" alt="Logo">
                    </div>
                </div>
                
                <div class="error-modal-body">
                    <h3 class="error-title">🎉 Password Reset Successful!</h3>
                    <p class="error-message-text">
                        <?php echo $_SESSION['reset_success']; ?>
                    </p>
                    <button class="try-again-btn" onclick="closeResetSuccessModal()">
                        Continue to Sign In
                    </button>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['reset_success']); ?>
        <?php endif; ?>

<!-- Forgot Password Success Modal - New Design -->
<?php if (isset($_SESSION['show_forgot_success'])): ?>
<div class="error-modal-overlay show" id="forgotSuccessModal">
    <div class="error-modal-card">
        <div class="error-modal-header">
            <div class="red-bar-left"></div>
            <div class="red-bar-right"></div>
            <div class="center-notch"></div>
            
            <div class="logo-circle">
                <img src="logo/logo.png" alt="Logo">
            </div>
        </div>
        
        <div class="error-modal-body">
            <h3 class="error-title">📧 Email Sent!</h3>
            <p class="error-message-text">
                <?php echo $_SESSION['forgot_success']; ?>
            </p>
            <p class="error-message-text" style="font-size: 13px; color: #888; margin-top: 10px;">
                Don't see the email? Check your spam folder or wait a few minutes for delivery.
            </p>
            <button class="try-again-btn" onclick="closeForgotSuccessModal()">
                Okay, Got It!
            </button>
        </div>
    </div>
</div>
<?php 
    unset($_SESSION['forgot_success']);
    unset($_SESSION['show_forgot_success']);
?>
<?php endif; ?>

<!-- OTP Verification Modal -->
<div class="otp-modal-overlay" id="otpModal" style="display: none;">
    <div class="otp-modal-card">
        <div class="otp-modal-header">
            <div class="otp-red-bar-left"></div>
            <div class="otp-red-bar-right"></div>
            <div class="otp-center-notch"></div>
            
            <div class="otp-logo-circle">
                <img src="logo/logo.png" alt="Logo">
            </div>
        </div>
        
        <div class="otp-modal-body">
            <h3 class="otp-modal-title">Verify Your Email</h3>
            <p class="otp-instruction">
                We've sent a 6-digit code to <span id="userEmail"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?></span>
            </p>
            
            <?php if(isset($_SESSION['info'])): ?>
                <div class="otp-success" style="display: block;">
                    <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($errors['otp-error'])): ?>
                <div class="otp-error" style="display: block;">
                    <?php echo $errors['otp-error']; ?>
                </div>
            <?php endif; ?>
            
            <form id="otpForm" method="POST" action="">
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" data-index="0" name="otp1" required autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="1" name="otp2" required autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="2" name="otp3" required autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="3" name="otp4" required autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="4" name="otp5" required autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="5" name="otp6" required autocomplete="off">
                </div>
                
                <div class="otp-timer">
                    <p id="timer">Resend available in <span id="countdown">60</span> seconds</p>
                </div>
                
                <div class="otp-error" id="otpError" style="display: none;"></div>
                <div class="otp-success" id="otpSuccess" style="display: none;"></div>
                
                <button type="submit" name="check" class="otp-verify-btn" id="verifyBtn" disabled>Verify OTP</button>
            </form>
            
           <form id="resendForm" method="POST" action="" 
            style="display:flex; justify-content:center; align-items:center; background:none; padding:0; margin:0;">

                <button type="submit" name="resend-otp" class="resend-link disabled" id="resendOtp">Resend OTP</button>
            </form>
        </div>
    </div>
</div>

<!-- Success Registration Modal -->
<div class="error-modal-overlay" id="successRegModal" style="display: none;">
    <div class="error-modal-card">
        <div class="error-modal-header">
            <div class="red-bar-left"></div>
            <div class="red-bar-right"></div>
            <div class="center-notch"></div>
            
            <div class="logo-circle">
                <img src="logo/logo.png" alt="Logo">
            </div>
        </div>
        
        <div class="error-modal-body">
            <h3 class="error-title">Registration Successful!</h3>
            <p class="error-message-text">
                Your account has been verified successfully. You can now sign in to your account.
            </p>
            <button class="try-again-btn" onclick="closeSuccessRegModal()">
                Continue to Sign In
            </button>
        </div>
    </div>
</div>

    <!-- Main Container -->
    <div class="container <?php echo (isset($_POST['signup']) || isset($_POST['name']) || (count($errors) > 0 && !empty($name))) ? 'active show-signup' : ''; ?>">
        <!-- Sign In Form -->
        <div class="form-box signin">
            <form action="" id="signinForm" method="POST" autocomplete="">
                <h1>Sign in</h1>
                    
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="forgot-link">
                    <a href="#" class="forgot-password-link">Forgot password?</a>
                </div>
                <button type="submit" name="signin" class="btn">Signin</button>
              
                <p>Or login with social platforms</p>
                <div class="social-icons">
                    <a href="<?php echo getGoogleLoginUrl(); ?>" title="Sign in with Google">
                        <i class="bx bxl-google"></i>
                    </a>
                    <a href="#" title="Coming soon"><i class="bx bxl-facebook"></i></a>
                </div>
           </form>
        </div>

        <!-- Sign Up Form -->
        <div class="form-box signup">
            <form action="" method="POST" autocomplete="">
                <h1>Sign up</h1>

                <div class="input-box">
                    <input type="text" name="name" placeholder="Full name" required value="<?php echo htmlspecialchars($name); ?>">
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
                    <i class="bx bxs-envelope"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="cpassword" placeholder="Confirm Password" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <button type="submit" name="signup" class="btn">Signup</button>
            </form>
        </div>

        <!-- Enhanced Loading Screen -->
        <div id="loading-screen" style="display:none;">
            <div class="loading-container">
                <div class="spinner"></div>
                <div class="loading-text">Processing your registration...</div>
                <div class="loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <div class="form-box forgot">
            <form action="" method="POST" id="forgotForm">
                <h1>Reset Password</h1>
                <h4>Enter your email address and we'll send you a link to reset your password</h4>
                
                <?php if(isset($errors['forgot-error'])): ?>
                    <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                        <i class="bx bx-error-circle"></i>
                        <?php echo $errors['forgot-error']; ?>
                    </div>
                <?php endif; ?>
                
                <div class="input-box">
                    <input type="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <i class="bx bxs-envelope"></i>
                </div>
                
                <button type="submit" name="forgot-password" class="btn">Send Reset Link</button>
            </form>
        </div>

        <div class="toggle-box">
            <!-- Left Panel (Welcome Back) -->
            <div class="toggle-panel toggle-left">
                <h1>Welcome back!</h1>
                <h4>Sign in to Zaf's Kitchen to manage <br>your catering events</h4>
                <p>Don't have an account?</p>
                <button class="btn signup-btn">Signup</button>
            </div>

            <!-- Right Panel (Join Now) -->
            <div class="toggle-panel toggle-right">
                <h1>Join now!</h1>
                <h4>let Zaf's Kitchen Bring flavor to your<br> celebrations</h4>
                <p>Already have an account?</p>
                <button class="btn signin-btn">Signin</button>
            </div>
        </div>
    </div>

<script>
    <?php if(isset($_SESSION['verification_success'])): ?>
        window.onload = function() {
            document.getElementById('successModal').style.display = 'flex';
            setTimeout(function() {
                document.getElementById('successModal').classList.add('fade-in');
            }, 50);
        }
    <?php endif; ?>

    <?php if(isset($_SESSION['reset_success'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('resetSuccessModal');
            if(modal) {
                modal.style.display = 'flex';
                setTimeout(() => modal.classList.add('show'), 50);
            }
        });
    <?php endif; ?>

    <?php if(isset($_SESSION['show_forgot_success'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('forgotSuccessModal');
            if(modal) {
                modal.style.display = 'flex';
                setTimeout(() => modal.classList.add('show'), 50);
            }
        });
    <?php endif; ?>

    <?php if((isset($_SESSION['show_otp_modal']) && $_SESSION['show_otp_modal'] === true) || isset($errors['otp-error'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showOTPModal();
        });
        <?php unset($_SESSION['show_otp_modal']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['show_success_reg_modal'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessRegModal();
        });
        <?php unset($_SESSION['show_success_reg_modal']); ?>
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {
        const successModal = document.getElementById('successModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeModal = document.getElementById('closeModal');
        
        if(closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                successModal.classList.remove('fade-in');
                setTimeout(function() { 
                    successModal.style.display = 'none';
                }, 300);
            });
        }
        
        if(closeModal) {
            closeModal.addEventListener('click', function() {
                successModal.classList.remove('fade-in');
                setTimeout(function() {
                    successModal.style.display = 'none';
                }, 300);
            });
        }

        const form = document.querySelector(".form-box.signup form");
        const loadingScreen = document.getElementById("loading-screen");
        
        if(form && loadingScreen) {
            form.addEventListener("submit", function () {
                loadingScreen.style.display = "flex";
            });
        }
    });

    function closeErrorModal() {
        const modal = document.getElementById("errorModal");
        if (modal) {
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    }

    function closeResetSuccessModal() {
        const modal = document.getElementById("resetSuccessModal");
        if (modal) {
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = "none";
                window.location.href = window.location.pathname;
            }, 300);
        }
    }

    function closeForgotSuccessModal() {
        const modal = document.getElementById("forgotSuccessModal");
        if (modal) {
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = "none";
                window.location.href = window.location.pathname;
            }, 300);
        }
    }

    function showOTPModal() {
        const modal = document.getElementById("otpModal");
        if (modal) {
            modal.style.display = "flex";
            setTimeout(() => {
                modal.classList.add("show");
            }, 50);
            startCountdown();
        }
    }

    function hideOTPModal() {
        const modal = document.getElementById("otpModal");
        if (modal) {
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    }

    function showSuccessRegModal() {
        hideOTPModal();
        setTimeout(() => {
            const modal = document.getElementById("successRegModal");
            if (modal) {
                modal.style.display = "flex";
                setTimeout(() => {
                    modal.classList.add("show");
                }, 50);
            }
        }, 400);
    }

    function closeSuccessRegModal() {
        const modal = document.getElementById("successRegModal");
        if (modal) {
            modal.classList.remove("show");
            setTimeout(() => {
                modal.style.display = "none";
                window.location.href = window.location.pathname;
            }, 300);
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.querySelector('.container');
        const signupBtn = document.querySelector('.signup-btn');
        const signinBtn = document.querySelector('.signin-btn');
        const forgotPasswordLink = document.querySelector('.forgot-password-link');

        if (signupBtn) {
            signupBtn.addEventListener('click', () => {
                container.classList.remove('forgot-active');
                container.classList.add('active');
            });
        }

        if (signinBtn) {
            signinBtn.addEventListener('click', () => {
                container.classList.remove('active', 'forgot-active');
            });
        }

        if (forgotPasswordLink) {
            forgotPasswordLink.addEventListener('click', (e) => {
                e.preventDefault();
                container.classList.remove('active');
                container.classList.add('forgot-active');
            });
        }

        initializeOTPModal();

        const otpModal = document.getElementById('otpModal');
        const expiry = localStorage.getItem('otp_expiry');
        if (otpModal && otpModal.style.display !== 'none' && expiry) {
            const now = Date.now();
            if (now < parseInt(expiry)) {
                startCountdown();
            } else {
                localStorage.removeItem('otp_expiry');
            }
        }
    });

    function initializeOTPModal() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const verifyBtn = document.getElementById('verifyBtn');
        const resendBtn = document.getElementById('resendOtp');
        const resendForm = document.getElementById('resendForm');

        if (!otpInputs.length) return;

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 1) this.value = this.value.slice(0, 1);
                this.classList.toggle('filled', this.value.length === 1);
                if (this.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                checkOTPComplete();
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace') {
                    if (this.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                        otpInputs[index - 1].value = '';
                        otpInputs[index - 1].classList.remove('filled');
                    } else {
                        this.value = '';
                        this.classList.remove('filled');
                    }
                    checkOTPComplete();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', function (e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                if (pastedData.length === 6) {
                    otpInputs.forEach((input, i) => {
                        input.value = pastedData[i] || '';
                        input.classList.toggle('filled', !!pastedData[i]);
                    });
                    checkOTPComplete();
                    verifyBtn.focus();
                }
            });

            input.addEventListener('focus', function () {
                this.select();
            });
        });

        if (resendForm) {
            resendForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!resendBtn.classList.contains('disabled')) {
                    resendOTP();
                }
            });
        }

        function checkOTPComplete() {
            const complete = Array.from(otpInputs).every(input => input.value.length === 1);
            if (verifyBtn) {
                verifyBtn.disabled = !complete;
                verifyBtn.classList.toggle('enabled', complete);
            }
        }
    }

    let countdownInterval;

    function startCountdown() {
        const resendBtn = document.getElementById('resendOtp');
        const countdownElement = document.getElementById('countdown');
        const timerElement = document.getElementById('timer');

        clearInterval(countdownInterval);

        let expiryTime = localStorage.getItem('otp_expiry');
        if (!expiryTime) {
            expiryTime = Date.now() + 60000;
            localStorage.setItem('otp_expiry', expiryTime);
        } else {
            expiryTime = parseInt(expiryTime);
        }

        function updateCountdown() {
            const remaining = Math.floor((expiryTime - Date.now()) / 1000);

            if (remaining >= 0 && countdownElement) {
                countdownElement.textContent = remaining;
            }

            if (remaining <= 0) {
                clearInterval(countdownInterval);
                if (timerElement) {
                    timerElement.innerHTML = '<span style="color: #DC2626; font-weight: bold;">You can now resend OTP</span>';
                }
                if (resendBtn) {
                    resendBtn.classList.remove('disabled');
                    resendBtn.style.pointerEvents = 'auto';
                    resendBtn.innerHTML = 'Resend OTP';
                }
                localStorage.removeItem('otp_expiry');
            }
        }

        updateCountdown();
        countdownInterval = setInterval(updateCountdown, 1000);

        if (resendBtn) {
            resendBtn.classList.add('disabled');
            resendBtn.style.pointerEvents = 'none';
            resendBtn.innerHTML = 'Resend OTP';
        }
    }

    function resendOTP() {
        const resendBtn = document.getElementById('resendOtp'); 
        showLoadingState(resendBtn, 'Sending...');

        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=resend-otp'
        })
            .then(response => response.json())
            .then(data => {
                hideLoadingState(resendBtn);
                if (data.success) {
                    showOTPMessage(data.message, 'success');
                    clearOTPInputs();
                    localStorage.setItem('otp_expiry', Date.now() + 60000);
                    startCountdown();
                } else {
                    showOTPMessage(data.message, 'error');
                }
            })
            .catch(error => {
                hideLoadingState(resendBtn);
                document.getElementById('resendForm').submit();
            });
    }

    function showLoadingState(element, loadingText = 'Loading...') {
        if (element) {
            element.disabled = true;
            element.dataset.originalText = element.innerHTML;
            element.innerHTML = `<span class="spinner"></span> ${loadingText}`;
        }
    }

    function hideLoadingState(element) {
        if (element && element.dataset.originalText) {
            element.disabled = false;
            element.innerHTML = element.dataset.originalText;
            delete element.dataset.originalText;
        }
    }

    function clearOTPInputs() {
        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            setTimeout(() => {
                input.value = '';
                input.classList.remove('filled');
                input.classList.add('shake');
                setTimeout(() => input.classList.remove('shake'), 500);
            }, index * 50);
        });

        setTimeout(() => {
            if (otpInputs[0]) otpInputs[0].focus();
        }, 300);
    }

    function showOTPMessage(message, type) {
        const errorElement = document.getElementById('otpError');
        const successElement = document.getElementById('otpSuccess');

        if (errorElement) errorElement.style.display = 'none';
        if (successElement) successElement.style.display = 'none';

        if (type === 'error' && errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        } else if (type === 'success' && successElement) {
            successElement.textContent = message;
            successElement.style.display = 'block';
        }
    }
</script>
</body>
</html>