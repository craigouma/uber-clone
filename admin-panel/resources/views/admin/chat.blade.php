
<head>
<style>
.container {
  border: 2px solid #dedede;
  background-color: #F8F8F8;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
  width:90%;
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
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}

.scroll {
  height: 400px;
  border: 1px dotted black;
  overflow-y: scroll; 
}

.m-scroll {
  height:80%;
  border: 1px dotted black;
  overflow-y: scroll; 
}

.m-scroll {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
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
  margin: 10px 0 !important;
  padding: 10px !important;
  border-radius: 5px !important;
  float: right !important;
}

.user_file {
  background-color:#F8F8F8 !important;
  border: 2px solid #dedede !important;
  margin: 10px !important;
  padding: 10px !important;
  border-radius: 5px !important;
  float: left !important;,
}

.mb-from{
    margin-right:10%;
    width:90%;
    flex-direction:row !important;
    display: flex !important;
    background-color:#F5F5F5;
    border-radius:10px;
    padding:10px;
}

.mb-to{
    margin-right:10%;
    width:90%;
    flex-direction:row-reverse !important;
    display: flex !important;
    background-color:#F5F5F5;
    border-radius:10px;
    padding:10px;
}

</style>
@livewireStyles
</head>
<body>
<livewire:customer-chat /> 
@livewireScripts
</body>
<script>
    (function()
{
  if( window.localStorage )
  {
    if( !localStorage.getItem('firstLoad') )
    {
      localStorage['firstLoad'] = true;
      window.location.reload();
    }  
    else
      localStorage.removeItem('firstLoad');
  }
})();
</script>