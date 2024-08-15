<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style>
.container {
  border: 2px solid #dedede;
  background-color: #F8F8F8;
  border-radius: 5px;
  padding: 20px;
  margin: 20px;
  width:70%;
  border-radius:20px;
  align-items: center;
  justify-content:center;
}

.darker {
  border-color: #ccc;
  background-color: #F8F8F8;
  flex-direction: row-reverse;
  margin-left: auto
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  border-radius: 50%;
}

.container img.right {
  float: right;
}

.time-right {
  float: right;
  color: #999;
}

.time-left {
  float: left;
  color: #999;
}

.scroll {
  height: 90%;
  width:100%;
  overflow-y: scroll; /* Add the ability to scroll */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.scroll::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.scroll {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.admin_file {
  background-color:#F8F8F8 !important;
  border: 2px solid #dedede !important;
  margin: 10px !important;
  padding: 10px !important;
  border-radius: 5px !important;
  float: left !important;
}

.user_file {
  background-color:#F8F8F8 !important;
  border: 2px solid #dedede !important;
  margin: 10px 0 !important;
  padding: 10px !important;
  border-radius: 5px !important;
  float: right !important;
}

</style>
@livewireStyles
</head>
<body>
<livewire:customer-chat /> 
@livewireScripts
</body>
</html>