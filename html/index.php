<?php session_start(); ?>
<html>
  <header>
    <meta charset='utf8'></meta>
    <title>Welcome!</title>
    <link href="mainpagestyle.css" rel="stylesheet" type="text/css">
  </header>
  <body>
        <div id='main'>
		<div id="header">
			<div id="logo"><p>Логотип супер крутого магазина!</p></div>
			<div id="controlpanel">
                <?php 
                    if (empty($_SESSION['login']) or empty($_SESSION['id'])) {
                        echo "<p>Вы не авторизированны на сатйе.<br><a  href='regauthpage.html'>Авторизация</a></p>";
                    } else {
                        echo "<p>Вы вошли на сайт, как ".$_SESSION['login']."<br><a  href='exit.php'>Выход</a></p>";
                    }
                ?>
			</div>
		</div>
		<div id="content">
    <?php 
      if (!empty($_SESSION['login']) and $_SESSION['pl'] == 1) {
        echo "<a href='additem.php'>Добавить товар</a>";
      }
    ?>
      <table>
        <tr><td>img</td><td>Наименование</td><td>Тип</td><td>Производитель</td><td>Цена</td><td>Рейтинг</td><td>Сортировка</td></tr>
        <tr><td>-</td><td><input type="text" id='nameFilter'></td><td><input type="text" id='typeFilter'></td>
        <td><input type="text" id='producerFilter'></td><td><input type="text" id='maxPriceFilter'></td>
        <td><input type="text" id='rankFilter'></td><td><select id='sort'>
          <option value='name'>по имени</option>
          <option value='price'>по цене</option>
          <option value='rank'>по рейтингу</option>
        </select><button>Применить</button></td></tr>
      </table>
			<table id="itemList"></table>
		</div>
		<div id="footer">
			<p>@ИУ4-12Б Архипов Артём.</p>
		</div>
	</div>

  <template>
    <tr class='item'>
      <td><img class='itemPhoto' width='50px' height='50px'></td>
      <td><a class='itemName'></a></td>
      <td class='itemType'></td>
      <td class='itemProducer'></td>
      <td class='itemPrice'></td>
      <td class='itemRank'></td>
    </tr>
  </template>

  </body>
</html>
<script>
  var items
  var _items

  fetch('http://ararh.ddns.net/getItems.php')
    .then((response) => {
      return response.json();
    }) 
    .then((data) => {
      items = data;
      _items = JSON.parse(JSON.stringify(data));
      renderItems();
    });
    
  function renderItems() {
    var fragment = document.createDocumentFragment();
    var template = document.querySelector('template');

    for (var i = 0; i < items.length; i++) {
    var node = template.content.cloneNode(true);
    node.querySelector('.itemPhoto').src = items[i]['photo']
    node.querySelector('.itemName').textContent = items[i]['name']
    node.querySelector('.itemName').setAttribute('href','http://ararh.ddns.net/itempage.php?itemID='+items[i]['ID'])
    node.querySelector('.itemType').textContent  = items[i]['type']
    node.querySelector('.itemProducer').textContent  = items[i]['producer']
    node.querySelector('.itemPrice').textContent  = items[i]['price']
    node.querySelector('.itemRank').textContent = items[i]['rank']
    fragment.appendChild(node);
    }

    document.querySelector('#itemList').appendChild(fragment);
  }

  function sortItems(type) {
    if (type == 'name') {items.sort((a, b) => a.name > b.name ? 1 : -1);}
    if (type == 'rank') {items.sort((a, b) => a.rank > b.rank ? -1 : 1);}
    if (type == 'price') {items.sort((a, b) => a.price > b.price ? 1 : -1);}
  }
  
  function removeItems() {
    var itemsList = document.querySelector('#itemList')
    var itemsCount = itemsList.childElementCount
    for(var i = 0; i < itemsCount; i++) {
      itemsList.removeChild(itemsList.lastElementChild);
    }
  }
  
  document.querySelector('button').onclick = function(evt) {
    items = JSON.parse(JSON.stringify(_items))
    evt.preventDefault()
    removeItems()

    items = items.filter(function(item) {
      var producerFilter = document.querySelector('#producerFilter').value
      if(producerFilter == '') {return item}
      if(item['producer'].includes(producerFilter)) {return item}
    })

    items = items.filter(function(item) {
      var typeFilter = document.querySelector('#typeFilter').value
      if(typeFilter == '') {return item}
      if(item['type'].includes(typeFilter)) {return item}
    })

    items = items.filter(function(item) {
      var nameFilter = document.querySelector('#nameFilter').value
      if(nameFilter == '') {return item}
      if(item['name'].includes(nameFilter)) {return item}
    })

    items = items.filter(function(item) {
      var maxPriceFilter = document.querySelector('#maxPriceFilter').value
      if (maxPriceFilter == '') {return item}
      if (item['price']-0 <= maxPriceFilter-0) {return item}
    })

    items = items.filter(function(item) {
      var rankFilter = document.querySelector('#rankFilter').value
      if (rankFilter == '') {return item}
      if (item['rank']-0 >= rankFilter-0) {return item}
    })

    sortItems(document.querySelector('#sort').value)
    renderItems()
  }
    
</script>