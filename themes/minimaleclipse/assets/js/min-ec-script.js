if (localStorage.getItem("min_ec_preferred_theme") == "dark") {
  setDarkMode(true);
}

function setDarkMode(isDark) {
  let min_ec_global_dark_button = document.getElementById("min-ec-dark-button");
  let min_ec_global_light_button = document.getElementById(
    "min-ec-light-button"
  );

  if (isDark) {
    min_ec_global_light_button.style.display = "block";
    min_ec_global_dark_button.style.display = "none";
    localStorage.setItem("min_ec_preferred_theme", "dark");
  } else {
    min_ec_global_light_button.style.display = "none";
    min_ec_global_dark_button.style.display = "block";
    localStorage.removeItem("min_ec_preferred_theme");
  }

  document.body.classList.toggle("darkmode");
}
jQuery(document).ready(function ($) {
  $(".min-ec-ajax-button").on("click", function () {
    $.ajax({
      url: eclipseScriptData.eclipseAjaxUrl,
      type: "GET",
      data: {
        action: "custom_ajax_projects",
        af_eic_ajax_nonce: eclipseScriptData.eclipseNonce,
      },
      success: function (response) {
        console.log(response);
      },
    });
  });
});
