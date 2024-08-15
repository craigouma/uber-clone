import React, { useEffect } from "react";
import {
  StyleSheet,
  View,
  Image,
  TouchableOpacity,
  Platform,
  PermissionsAndroid,
  StatusBar
} from "react-native";
import {
  bold,
  logo,
  app_name,
  LATITUDE_DELTA,
  LONGITUDE_DELTA,
  app_settings,
  api_url 
} from "../config/Constants";
import { useNavigation, CommonActions } from "@react-navigation/native";
import * as colors from "../assets/css/Colors";
import { connect } from 'react-redux';
import { initialLat, initialLng, initialRegion } from '../actions/BookingActions';
import AsyncStorage from '@react-native-async-storage/async-storage';
//import RNAndroidLocationEnabler from 'react-native-android-location-enabler';
import Geolocation from '@react-native-community/geolocation';
import PushNotificationIOS from "@react-native-community/push-notification-ios";
import PushNotification, { Importance } from "react-native-push-notification";
import VersionNumber from 'react-native-version-number';

import axios from 'axios';
const Splash = (props) => {
  const navigation = useNavigation();
  const app_version_code = VersionNumber.buildVersion;

  useEffect(() => {
    check_data();
  }, []);

  const check_data = async () => {
    if (Platform.OS == "android") {
      configure();
      channel_create();
      call_settings();
      //global.fcm_token = '123456'
    } else {
      //global.fcm_token = '123456'
      call_settings();
    }
  }

  const channel_create = () => {
    PushNotification.createChannel(
      {
        channelId: "taxi_booking", // (required)
        channelName: "Booking", // (required)
        channelDescription: "Taxi Booking Solution", // (optional) default: undefined.
        playSound: true, // (optional) default: true
        soundName: "uber.mp3", // (optional) See `soundName` parameter of `localNotification` function
        importance: Importance.HIGH, // (optional) default: Importance.HIGH. Int value of the Android notification importance
        vibrate: true, // (optional) default: true. Creates the default vibration pattern if true.
      },
      (created) => console.log(`createChannel returned '${created}'`) // (optional) callback returns whether the channel was created, false means it already existed.
    );
  }

  const configure = () => {
    PushNotification.configure({
      // (optional) Called when Token is generated (iOS and Android)
      onRegister: function (token) {
        global.fcm_token = token.token;
      },

      // (required) Called when a remote is received or opened, or local notification is opened
      onNotification: function (notification) {
        console.log("NOTIFICATION:", notification);

        // process the notification

        // (required) Called when a remote is received or opened, or local notification is opened
        notification.finish(PushNotificationIOS.FetchResult.NoData);
      },

      // (optional) Called when Registered Action is pressed and invokeApp is false, if true onNotification will be called (Android)
      onAction: function (notification) {
        console.log("ACTION:", notification.action);
        console.log("NOTIFICATION:", notification);

        // process the action
      },

      // (optional) Called when the user fails to register for remote notifications. Typically occurs when APNS is having issues, or the device is a simulator. (iOS)
      onRegistrationError: function (err) {
        console.error(err.message, err);
      },

      // IOS ONLY (optional): default: all - Permissions to register.
      permissions: {
        alert: true,
        badge: true,
        sound: true,
      },

      // Should the initial notification be popped automatically
      // default: true
      popInitialNotification: true,

      /**
       * (optional) default: true
       * - Specified if permissions (ios) and token (android and ios) will requested or not,
       * - if not, you must call PushNotificationsHandler.requestPermissions() later
       * - if you are not using remote notification or do not have Firebase installed, use this:
       *     requestPermissions: Platform.OS === 'ios'
       */
      requestPermissions: true,
    });
  }

  const check_location = async () => {
    await getInitialLocation();
  }

  const call_settings = async () => {
    await axios({
      method: 'get',
      url: api_url + app_settings
    })
      .then(async response => {
        if(response.data.result.android_latest_version.version_code > app_version_code){
          navigate_update_app('https://play.google.com/store/apps/details?id=com.cab2u_proj.customer');
        }else{
          home(response.data.result);
        }
      })
      .catch(error => {
        console.log(error)
      });
  }

  const navigate_update_app = (url) =>{
    navigation.dispatch(
      CommonActions.reset({
        index: 0,
        routes: [{ name: "AppUpdate", params: {url: url} }],
      })
    );
  }

  const home = async (data) => {
    global.lang = 'en';
    const id = await AsyncStorage.getItem('id');
    const first_name = await AsyncStorage.getItem('first_name');
    const profile_picture = await AsyncStorage.getItem('profile_picture');
    const phone_with_code = await AsyncStorage.getItem('phone_with_code');
    const email = await AsyncStorage.getItem('email');
    const lang = await AsyncStorage.getItem('lang');
    global.existing = await AsyncStorage.getItem("existing");
    global.stripe_key = await data.stripe_key;
    global.razorpay_key = await data.razorpay_key;
    global.paystack_public_key = await data.paystack_public_key;
    global.paystack_secret_key = await data.paystack_secret_key;
    global.flutterwave_public_key = await data.flutterwave_public_key;
    global.app_name = await data.app_name;
    global.language_status = await data.language_status;
    global.default_language = await data.default_language;
    global.polyline_status = await data.polyline_status;
    global.currency = await data.default_currency_symbol;
    global.mode = data.mode;
    global.promo_id = 0;
    /*if(global.language_status == 1){
       global.lang = await global.default_language;
    }
 
    if(lang){
      strings.setLanguage(lang);
      global.lang = lang;
    }else{
      strings.setLanguage('en');
      global.lang = 'en';
    }
 
   if(global.lang == 'en' && I18nManager.isRTL){
     I18nManager.forceRTL(false);
     await RNRestart.Restart();
   }
 
   if(global.lang == 'ar' && !I18nManager.isRTL){
     I18nManager.forceRTL(true);
     await RNRestart.Restart();
   }*/

    if (id !== null) {
      global.id = id;
      global.first_name = first_name;
      global.profile_picture = profile_picture;
      global.phone_with_code = phone_with_code;
      global.email = email;
      check_location();
    } else {
      global.id = 0;
      check_location();
    }
  }

  const getInitialLocation = async () => {
    Geolocation.getCurrentPosition(async (position) => {
      let location = position.coords;
      let region = {
        latitude: location.latitude,
        longitude: location.longitude,
        latitudeDelta: LATITUDE_DELTA,
        longitudeDelta: LONGITUDE_DELTA
      }
      await props.initialRegion(region);
      await props.initialLat(location.latitude);
      await props.initialLng(location.longitude);
      /*if(this.state.redirection_mode == 1){
        this.navigate_home();
      }else{
        this.navigate_login();
      }*/
      navigate();
    }, error => navigation.navigate('LocationEnable'),
      { enableHighAccuracy: false, timeout: 10000 });
  }

  const navigate = () => {
    if (global.existing == 1) {
      if (global.id > 0) {
        navigation.dispatch(
          CommonActions.reset({
            index: 0,
            routes: [{ name: "Home" }],
          })
        );
      } else {
        navigation.dispatch(
          CommonActions.reset({
            index: 0,
            routes: [{ name: "CheckPhone" }],
          })
        );
      }
    }else {
      navigation.dispatch(
        CommonActions.reset({
          index: 0,
          routes: [{ name: "Intro" }],
        })
      );
    }
  }

  return (
    <TouchableOpacity activeOpacity={1} onPress={navigate} style={styles.background}>
      <StatusBar
        backgroundColor={colors.theme_bg}
      />
      <View style={styles.logo_container}>
        <Image style={styles.logo} source={logo} />
      </View>
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  background: {
    height: "100%",
    width: "100%",
    alignItems: "center",
    justifyContent: "center",
    backgroundColor: colors.theme_bg_three,
  },
  logo_container: {
    height: 196,
    width: 196,

  },
  logo: {
    height: undefined,
    width: undefined,
    flex: 1,
    borderRadius: 98
  },
  spl_text: {
    fontFamily: bold,
    fontSize: 18,
    color: colors.theme_fg_three,
    letterSpacing: 2,
  },
});

function mapStateToProps(state) {
  return {
    initial_lat: state.booking.initial_lat,
    initial_lng: state.booking.initial_lng,
    initial_region: state.booking.initial_region,
  };
}

const mapDispatchToProps = (dispatch) => ({
  initialLat: (data) => dispatch(initialLat(data)),
  initialLng: (data) => dispatch(initialLng(data)),
  initialRegion: (data) => dispatch(initialRegion(data))
});

export default connect(mapStateToProps, mapDispatchToProps)(Splash);
