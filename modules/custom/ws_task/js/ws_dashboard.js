(function ($, Drupal, drupalSettings) {

  var isPersonalized = (drupalSettings.advisor_services)?
  drupalSettings.advisor_services.advisor:null;
  $('#edit-crd').keydown(function (e){
    var crdNo = $('#edit-crd').val();
    if(e.keyCode == 13 && crdNo){
      e.preventDefault();
      getCrd();
    }
  });
 
  $('#edit-sales-code').keydown(function (e){
    var salesCode = $('#edit-sales-code').val();
    if(e.keyCode == 13 && salesCode){
      e.preventDefault();
      getCrd();
    }
  });

  function getCrd() {
    $("span.error").remove();
    var crdNo = $('#edit-crd').val();
    var salesCode = $('#edit-sales-code').val();
    var baseURL = drupalSettings.path.baseUrl + "core/misc/throbber-active.gif";
    $('#edit-container' ).before( '<span class="error"><img src="'+ baseURL +'"></span>' );
    $('#edit-sales-code').attr('readonly', true);
    $('#edit-crd').attr('readonly', true);
    //$('.button-crd').attr('disabled', 'disabled');
     
    //Personalized scenario   
    if(isPersonalized){
      $.when( $.get('/advisor/personalise_crd/'+ crdNo)).then( populateAdvidorData, myFailure );
    }else{
    // Non-personalized scenario
      $.when( $.get('/advisor/data/'+ crdNo + '/' + salesCode)).then( crdDetails, myFailure );
    }
  }
 
  //Next button below crd.
  if($('#edit-container').length){
     $('#edit-container' ).after('<div id="button-crd" class="form-wrapper"> '+
      '<input type="button" class="button-crd" value="Next"></div>');
  }
 
  $('.button-crd').on('click', function(e) {
    var crdNo = $('#edit-crd').val();
    var salesCode = $('#edit-sales-code').val();

    if (isPersonalized && crdNo == '') {
      return;
    }else if(!isPersonalized && salesCode == '' || crdNo == ''){
      return;
    }
    getCrd();
  });
 
  //Success callback.
  var crdDetails = function(response) {

    displayHiddenFields();
    $('input[name="contact_id"]').val(response.contactid);
    //$('input[name="contact_type"]').val(response.contacttype);
    $('input[name="isonboarded"]').val(response.isonboarded);
    $('#edit-first-name').val(response.first_name);
    $('#edit-last-name').val(response.last_name);
    $('#edit-email-mail-1').val(response.email);
    $('#edit-email-mail-2').val(response.email);
    $('#edit-preferred-email').val(response.email);
    $('#edit-mother-s-maiden-name').val(response.mother_maiden_name);
    $('#edit-taxpayer-id').val(response.tax_id);
    //$('#edit-pin').val(response.pin);
    $('#edit-work-phone-number').val(response.workphone);
    $('#edit-extension').val(response.extension);
    $('#edit-mobile-phone-number').val(response.mobile);
    $('#edit-address-line1').val(response.address1);
    $('#edit-address-line2').val(response.address2);
    $('#edit-city').val(response.city);
    $('#edit-zipcode').val(response.zipcode);
    $('#edit-state').val(response.state);
   
    //Contact section
    $('input[name="internalae_name"]').val(response.internalae.name);
    $('input[name="internalae_number"]').val(response.internalae.number);
    $('input[name="internalae_mail"]').val(response.internalae.email);

    $('input[name="external_name"]').val(response.external.name);
    $('input[name="external_number"]').val(response.external.number);
    $('input[name="external_mail"]').val(response.external.email);

    $('input[name="service_team_name"]').val(response.service_team.name);
    $('input[name="service_team_number"]').val(response.service_team.number);
    $('input[name="service_team_mail"]').val(response.service_team.email);

     $('input[name="firm_id"]').val(response.firm.firmid);
     $('input[name="broker_dealer_id"]').val(response.firm.brokerdealerid);

    if(!jQuery.isEmptyObject(response)){
      localStorage.setItem("firmData", JSON.stringify(response.firm));
    }else{
      $('#edit-container' ).before('<span class="error">' + response + '</span>');
      UnlockedFields();
    }   
    removeThrobber();
  }
   
  //Failure callback.
  var myFailure = function (error) {
    removeThrobber();
    UnlockedFields();
    if(error.status == 404){
      $('#edit-container' ).before('<span class="error">We \'re sorry, you appear to have entered an invalid combination.  Please double check your entry and contact your sales rep if necessary.</span>');
    }else{
      var redirectURL = drupalSettings.path.baseUrl + "node/16";
      window.location.href = redirectURL;
      //$('#edit-container' ).before('<span class="error">' + error.status + ': ' + error.statusText + '</span>');
    }
    $('#webform-submission-registration-add-form')[0].reset();
  }

  var fetchFailure = function (error) {
     console.log('Error: '+ JSON.stringify(error));
  }
  //Populate crd firm data
  var existingFirmData = JSON.parse(localStorage.getItem('firmData'));
  if($('#edit-firm-crd').length && existingFirmData.crd) {
    $('input[name="firm_id"]').val(existingFirmData.firmid);
    $('input[name="broker_dealer_id"]').val(existingFirmData.brokerdealerid);
    $('#edit-firm-crd').val(existingFirmData.crd);
    $('#edit-firm-name').val(existingFirmData.name);
    $('#edit-broker-dealer-name').val(existingFirmData.broker_name);
    localStorage.removeItem('firmData');   
  }
   
  //Populate advisor registration form data.
  function populateAdvidorData(advisorData) {
 
    if($('#block-advisor-content').length){   
      displayHiddenFields();
      $('input[name="contact_id"]').val(advisorData.contactid);
      //$('input[name="contact_type"]').val(advisorData.contacttype);
      $('input[name="isonboarded"]').val(advisorData.isonboarded);
      $('#edit-first-name').val(advisorData.first_name);
      $('#edit-last-name').val(advisorData.last_name);
      $('#edit-email-mail-1').val(advisorData.email);
      $('#edit-email-mail-2').val(advisorData.email);
      $('#edit-preferred-email').val(advisorData.email);
      $('#edit-mother-s-maiden-name').val(advisorData.mother_maiden_name);
      $('#edit-taxpayer-id').val(advisorData.tax_id);
      //$('#edit-pin').val(advisorData.pin);
      $('#edit-work-phone-number').val(advisorData.workphone);
      $('#edit-extension').val(advisorData.extension);
      $('#edit-mobile-phone-number').val(advisorData.mobile);
      $('#edit-address-line1').val(advisorData.address1);
      $('#edit-address-line2').val(advisorData.address2);
      $('#edit-city').val(advisorData.city);
      $('#edit-zipcode').val(advisorData.zipcode);
      $('#edit-state').val(advisorData.state);

      //Contact section
      $('input[name="internalae_name"]').val(advisorData.internalae.name);
      $('input[name="internalae_number"]').val(advisorData.internalae.number);
      $('input[name="internalae_mail"]').val(advisorData.internalae.email);

      $('input[name="external_name"]').val(advisorData.external.name);
      $('input[name="external_number"]').val(advisorData.external.number);
      $('input[name="external_mail"]').val(advisorData.external.email);

      $('input[name="service_team_name"]').val(advisorData.service_team.name);
      $('input[name="service_team_number"]').val(advisorData.service_team.number);
      $('input[name="service_team_mail"]').val(advisorData.service_team.email);

      $('input[name="firm_id"]').val(advisorData.firm.firmid);
      $('input[name="broker_dealer_id"]').val(advisorData.firm.brokerdealerid);
      removeThrobber();
    }
    localStorage.setItem("firmData", JSON.stringify(advisorData.firm));
  }
 
  //Show once data is populated.
  function displayHiddenFields(){
    $('#edit-actions-wizard-next, #edit-email--wrapper, #edit-container-02,'+
    ' #edit-container-03, #edit-container-04, #edit-container-05, #edit-container-06,'+
    ' #edit-container-10').show();
    $('#button-crd').remove();
  }
 
  //Remove throbber.
  function removeThrobber(){
    $("span.error").remove();
    //$('#edit-crd').attr('readonly', false);   
    //$('#edit-sales-code').attr('readonly', false);
    setTimeout(function() {
        $('input[id="edit-crd"]').prev('span').remove();
    }, 4000);
  }

  function UnlockedFields(){
    $('#edit-crd').attr('readonly', false);   
    $('#edit-sales-code').attr('readonly', false);
  }
 
  //Get firm information.
  $('#edit-firm-crd').keydown(function (e){
    var firmCrd = $('#edit-firm-crd').val();
    if(e.keyCode == 13 && firmCrd && existingFirmData.crd != firmCrd){
      e.preventDefault();
      var baseURL = drupalSettings.path.baseUrl + "core/misc/throbber-active.gif";
      $( 'input[id="edit-firm-crd"]' ).before( '<span class="error"><img src="'+ baseURL +'" class="throbber"></span>' );
      $('#edit-firm-crd').attr('readonly', true);
      $.when( $.get('/advisor/firm/'+ firmCrd)).then( function (firmData) {

        if(firmData.firmid){
          $('input[name="firm_id"]').val(firmData.firmid);
          $('input[name="broker_dealer_id"]').val(firmData.brokerdealerid);
          $('#edit-firm-crd').val(firmData.crd);
          $('#edit-firm-name').val(firmData.name);
          //$('#edit-broker-dealer-name').val(firmData.broker_name);
        }
        //$('#edit-container-07' ).before('<span class="error">' + JSON.stringify(firmData) + '</span>');
       
        $("span.error").remove();
        $('#edit-firm-crd').attr('readonly', false);
      }, function (error) {
        if(error.status == 404){
          $('input[id="edit-firm-crd"]' ).prev('span').remove();
          $("span.error").remove();
          //$('#edit-container-07' ).before('<span class="error">We do not recognize the Firm CRD you entered.  Please double check your entry and contact your SEI sales representative if necessary</span>');
          //$('#edit-firm-crd').val('');
          $('#edit-firm-name').val('');
          //$('#edit-broker-dealer-name').val('');
          setTimeout(function() {
            $('input[id="edit-firm-crd"]').prev('span').remove();
          }, 4000);
        }else{
          $('#edit-container-07' ).before('<span class="error">' + error.status + '</span>');
        }
        $('input[id="edit-firm-crd"]').next('span').remove();
        $('#edit-firm-crd').attr('readonly', false);
      } );
    }else if(e.keyCode == 13 && existingFirmData.crd == firmCrd){
      $('input[name="firm_id"]').val(existingFirmData.firmid);
      $('input[name="broker_dealer_id"]').val(existingFirmData.brokerdealerid);
      $('#edit-firm-crd').val(existingFirmData.crd);
      $('#edit-firm-name').val(existingFirmData.name);
      $('#edit-broker-dealer-name').val(existingFirmData.broker_name);
    }
  });


  //Load default states
  /*
  var states = localStorage.getItem('states')?localStorage.getItem('states'):'';
  if(!states){
    $.when( $.get('/advisor/states')).then(statesDetails, fetchFailure );
  }

  function statesDetails(states) {
    if(!jQuery.isEmptyObject(states)){
      localStorage.setItem("states", JSON.stringify(states));
    }
  }
 */

  //Load default Brokers.
/* 
  var brokerDealers = localStorage.getItem('brokerDealers')?
  localStorage.getItem('brokerDealers'):'';
  if(!brokerDealers){
    $.when( $.get('/advisor/brokerdealer')).then(brokerDetails, fetchFailure );
  }

  function brokerDetails(brokers) {
    if(!jQuery.isEmptyObject(brokers)){
      localStorage.setItem("brokerDealers", JSON.stringify(brokers));
    }
  }
*/
 
  //Default setting for msg-box
  $('#edit-broker-dealer-name').on("change keyup blur input", function() {
    var brokName = $('#edit-broker-dealer-name').val();
    if(brokName == ""){
      $(".msg-box").hide(); 
      $(".form-item-hybridfirm").hide(); 
     }
    });

  $('#edit-broker-dealer-name').keydown(function (e){
    var brokName = $('#edit-broker-dealer-name').val();
    if(brokName == ""){
      $(".msg-box").hide(); 
      $(".form-item-hybridfirm").hide(); 
      $('#edit-hybridfirm').attr('checked', false);
    }
  });

  var brokName = $('#edit-broker-dealer-name').val();
  if(brokName != ''){
    $(".msg-box").show();
    $(".form-item-hybridfirm").show();
  }

  $('#edit-broker-dealer-name').on('autocompleteselect', function (e, ui) {
    if(ui.item.value){
      $(".msg-box").show();
      $(".form-item-hybridfirm").show();
    }
  });

  // Get crd data on previous button click.
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  };

  //Display hidden data.
  var page = getUrlParameter('page');
  if(page == 'your_information'){
    if ($('#edit-crd').val() != '') {
      displayHiddenFields();
      if ($('#edit-sales-code').length) {
        $('#edit-sales-code').attr('readonly', true);
      }
      $('#edit-crd').attr('readonly', true);
    }
  }

  //Drupal behaviours
  Drupal.behaviors.advisor_services = {
    attach: function (context, settings) {
      var isPersonalized = (drupalSettings.advisor_services)?
      drupalSettings.advisor_services.advisor:null;
      if(isPersonalized){
        //$('.form-item-sales-code').remove();
      }else{
        $('.form-item-sales-code').show();
      }
    }
  };

})(jQuery, Drupal, drupalSettings);