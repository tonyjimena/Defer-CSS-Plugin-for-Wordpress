jQuery(document).ready(function ($) {
  document.querySelector("#mainContainer").innerHTML = mainTemplate;
  document.querySelector(".loader").style.display = "block";

  addIframe();
  // Check All function
  $("#checkAll").click(function (event) {
    if (this.checked) {
      // Iterate each checkbox
      $(":checkbox").each(function () {
        this.checked = true;
      });
    } else {
      $(":checkbox").each(function () {
        this.checked = false;
      });
    }
  });

  // On Submit ajax form function
  $("#ajaxForm").submit(function (e) {
    e.preventDefault();
    document.querySelector(".loader").style.display = "block";
    var names = [];
    var links = [];
    var checkBoxes = document.querySelectorAll(
      "input[type=checkbox][name='name']"
    );
    checkBoxes.forEach((box) => {
      if (box.checked) {
        names.push(box.value);
        links.push(box.parentElement.previousElementSibling.textContent);
      }
    });

    ayax_request_defer(names, links);
  });
});

// añade el iframe

function addIframe() {
  var newIframe = document.createElement("iframe");
  newIframe.onload = function () {
    setTimeout(() => {
        document.querySelector(".loader").style.display = "none";
        iframe_data();
    }, 500)
  };
  newIframe.src = `http://${document.domain}`;

  newIframe.classList.add("framer");

  var currentDiv = document.getElementById("iframeContainer");
  currentDiv.appendChild(newIframe);
}

//function recoje datos del iframe

function iframe_data() {
  var links = document
    .querySelector(".framer")
    .contentWindow.document.querySelectorAll("link[rel=stylesheet]");

  var ids = [];
  var hrefs = [];

  links.forEach((link) => {
    if (link.id.substring(0, link.id.length - 4).length > 0) {
      ids.push(link.id.substring(0, link.id.length - 4));
      hrefs.push(link.href);
    }
  });

  addStyleShow(ids, hrefs);
}

//Muestra la lista

function addStyleShow(stylesId, stylesHref) {
  var form = document.querySelector("#ajaxForm");
  form.style.display = "block";
  var tableBody = document.getElementById("tableBody");
  tableBody.innerHTML = "";

  for (let index = 0; index < stylesId.length; index++) {
    var template = `
      <tr>
          <td data-label="Name">${stylesId[index]}</td>
          <td data-label="Link"><a href='${stylesHref[index]}' target="_blank">${stylesHref[index]}</a></td>
          <td data-label="Check"><input type="checkbox" name='name' value='${stylesId[index]}' /></td>
      </tr>`;

    tableBody.innerHTML += template;
  }
  document.querySelector('#counterNames').innerHTML = `(${stylesId.length})`

}

//Llamada Ajax

function ayax_request_defer(ids, hrefs) {
  var data = {
    action: "my_action",
    ids: ids,
    hrefs: hrefs,
  };

  jQuery.post(ajaxurl, data, function (response) {
    document.querySelector(".loader").style.display = "none";
    if ((response = "okey")) {
      document.querySelector("#response").classList.add("okey");
      document.querySelector("#response").innerHTML = "Listo!";
    } else {
      document.querySelector("#response").classList.add("fail");
      document.querySelector("#response").innerHTML = "Algo fué mal!";
    }
  });
}

//plantilla principal del menu admin

var mainTemplate = `
        <h1>Defer CSS Plugin ✔️</h1>
        <p>Este plugin mueve la carga de todas las hojas de estilo al footer, por defecto se renderizan en el head, esto penaliza la performance y el SEO</p>
        <div class="stylesShow">
            <div id="functionContainer">
                <form id="ajaxForm">
                <table id="stylesList">
                <thead>
                    <tr>
                    <th scope="col">Nombre<span id="counterNames"></span></th>
                    <th scope="col">Link</th>
                    <th scope="col"><input type="checkbox" id="checkAll"/></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    
                </tbody>
                </table>
                    <div class="submitWrapper">
                    <input type="submit" class="myButton" value='Defer CSS!'></input>
                    </div>
                </form>
                <div class="responseWrapper">
                    <div id="loader" class="loader"></div>
                    <div id="response"></div>
                </div>
            </div>
        </div>`;
