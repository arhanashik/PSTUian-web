// do things when the document is ready
$(function() {
    registerDevice();
});

//  device id store variable name
let deviceIdStore = "device_id";
// get deice id 
const getDeviceId = () => localStorage.getItem(deviceIdStore);
// set device id
const setDeviceId = (deviceId) => localStorage.setItem(deviceIdStore, deviceId);

// register device
function registerDevice() {
  var deviceId = getDeviceId();
  // if device is already registered, return
  if(deviceId && deviceId !== null && deviceId !== '') {
      return;
  }
  
  // if device id does not exists, generate a new one
  deviceId = String(
      Date.now().toString(32) +
      Math.random().toString(32) +
      Math.random().toString(32)
  ).replace(/\./g, '');

  let data = {
    id: deviceId,
    fcm_token: 'unknown',
    model: navigator['platform'],
  }
  $.ajax({
      url: `${baseUrl}device.php?call=register`,
      type:'post',
      data: data,
      success:function(response) {
        let result = JSON.parse(response);
        if(result['success'] === true) {
          // if registration was successful, update device id
          setDeviceId(deviceId);
        }
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
  });
}