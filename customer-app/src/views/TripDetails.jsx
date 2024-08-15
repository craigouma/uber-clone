import React, { useState, useEffect, useRef } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    ScrollView,
    Image,
    StatusBar,
    FlatList,
    Linking,
    Alert,
    Platform,
    PermissionsAndroid
} from "react-native";
import { useNavigation, useRoute, CommonActions } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, GOOGLE_KEY, screenWidth, normal, bold, app_name, sos, regular, api_url, trip_details, img_url, get_tips, add_tip, trip_cancel, loader, sos_sms } from '../config/Constants';
import BottomSheet from 'react-native-simple-bottom-sheet';
import Icon, { Icons } from '../components/Icons';
import MapView, { Marker, PROVIDER_GOOGLE, AnimatedRegion, MarkerAnimated, Polyline } from 'react-native-maps';
import DropShadow from "react-native-drop-shadow";
import LottieView from 'lottie-react-native';
import { Badge } from 'react-native-paper';
import { connect } from 'react-redux';
import axios from 'axios';
import Dialog from "react-native-dialog";
import database from '@react-native-firebase/database';
import DropdownAlert, {
    DropdownAlertData,
    DropdownAlertType,
  } from 'react-native-dropdownalert';
import Geolocation from '@react-native-community/geolocation';
import { decode, encode } from "@googlemaps/polyline-codec";

const TripDetails = (props) => {
    const navigation = useNavigation();
    const route = useRoute();
    const map_ref = useRef();
    //const driver_loc = useRef();
    let alt = useRef(
        (_data?: DropdownAlertData) => new Promise<DropdownAlertData>(res => res),
    );
    const [region, setRegion] = useState(props.initial_region);
    const [loading, setLoading] = useState(false);
    const [cancel_loading, setCancelLoading] = useState(false);
    const [data, setData] = useState(route.params.data);
    const [trip_id, setTripId] = useState(route.params.trip_id);
    const [from, setFrom] = useState(route.params.from);
    const [dialog_visible, setDialogVisible] = useState(false);
    const [driver_track, setDriverTrack] = useState(null);
    const [coords, setCoords] = useState([]);
    const [on_load, setOnLoad] = useState(0);
    const [tips, setTips] = useState([]);
    const [tip, setTip] = useState(0);
    const [is_mount, setIsMount] = useState(0);
    const [pickup_statuses, setPickupStatuses] = useState([1, 2]);
    const [cancellation_reason, setCancellationReasons] = useState([]);
    const [cancellation_statuses, setCancellationStatuses] = useState([6, 7]);
    const [drop_statuses, setDropStatuses] = useState([3, 4]);
    const [driver_location, setDriverLocation] = useState({ latitude: 9.914372, longitude: 78.155033 });
    const [driver_location_ios, setDriverLocationIos] = useState(new AnimatedRegion({ latitude: 9.914372, longitude: 78.155033 }));
    const [home_marker, setHomeMarker] = useState({ latitude: parseFloat(route.params.data.trip.pickup_lat), longitude: parseFloat(route.params.data.trip.pickup_lng) });
    const [destination_marker, setDestinaionMarker] = useState({ latitude: parseFloat(route.params.data.trip.drop_lat), longitude: parseFloat(route.params.data.trip.drop_lng) });
    const [bearing, setBearing] = useState(0);
    const go_back = () => {
        if (from == 'home') {
            navigation.navigate('Dashboard')
        } else {
            navigation.goBack();
        }
    }

    const showDialog = () => {
        setDialogVisible(true);
    }
    axios.interceptors.request.use(async function (config) {
        // Do something before request is sent
        //console.log("loading")
        setLoading(true);
        return config;
    }, function (error) {
          console.log(error)
          setLoading(false);
          console.log("finish loading")
          // Do something with request error
        return Promise.reject(error);
    });
    useEffect(() => {
        call_get_tips();
        call_trip_details();
        const onValueChange = database().ref(`/trips/${trip_id}`)
            .on('value', snapshot => {
                if (snapshot.val().status != data.status) {
                    call_trip_details();
                }
            });
        const onDriverTracking = database().ref(`/drivers/${data.trip.vehicle_type}/${data.trip.driver_id}`)
            .on('value', snapshot => {
                if (snapshot.val()) {
                    let marker = {
                        latitude: parseFloat(snapshot.val().geo.lat),
                        longitude: parseFloat(snapshot.val().geo.lng)
                    }
                    if (data.trip.status <= 2) {
                        get_direction(snapshot.val().geo.lat + "," + snapshot.val().geo.lng, data.trip.pickup_lat + "," + data.trip.pickup_lng)
                    } else {
                        get_direction(snapshot.val().geo.lat + "," + snapshot.val().geo.lng, data.trip.drop_lat + "," + data.trip.drop_lng)
                    }
                    setBearing(snapshot.val().geo.bearing)
                    setDriverLocation(marker)
                    setDriverLocationIos(marker)
                    animate(marker);
                }
            });
        return (
            onValueChange,
            onDriverTracking
        );
    }, []);

    const get_direction = async (startLoc, destinationLoc) => {
        try {
            let resp = await fetch(`https://maps.googleapis.com/maps/api/directions/json?origin=${startLoc}&destination=${destinationLoc}&key=${GOOGLE_KEY}`)
            let respJson = await resp.json();
            let points = decode(respJson.routes[0].overview_polyline.points, 5);
            let coords = points.map((point, index) => {
                return {
                    latitude: point[0],
                    longitude: point[1]
                }
            })
            setCoords(coords);
        } catch (error) {
            console.log(error)
            //return error
        }
    }

    const animate = (nextProps) => {
        const duration = 500;
        if (driver_location !== nextProps) {
            if (Platform.OS === 'android') {
                if (driver_track) {
                    driver_track.animateMarkerToCoordinate(
                        nextProps,
                        duration
                    )
                }
            } else {
                driver_location_ios.timing({
                    ...nextProps,
                    duration
                }).start();
            }
        }
    }

    const call_get_tips = () => {
        
        axios({
            method: 'post',
            url: api_url + get_tips,
            data: { trip_id: trip_id }
        })
            .then(async response => {
                setLoading(false);
                setTips(response.data.result['data']);
                setTip(response.data.result['tip']);
            })
            .catch(error => {
                setLoading(false);
                alert('Sorry something went wrong')
            });
    }

    const call_add_tip = (tip) => {
        
        axios({
            method: 'post',
            url: api_url + add_tip,
            data: { trip_id: trip_id, tip }
        })
            .then(async response => {
                setLoading(false);
                if (response.data.status == 1) {
                    alt({
                        type: DropdownAlertType.Success,
                        title: 'Thank You',
                        message: 'Your tip added successfully!',
                      });
                    
                    setTip(tip);
                }
            })
            .catch(error => {
                setLoading(false);
                alert('Sorry something went wrong')
            });
    }

    const call_trip_details = () => {
        
        axios({
            method: 'post',
            url: api_url + trip_details,
            data: { trip_id: trip_id }
        })
            .then(async response => {
                setLoading(false);
                setData(response.data.result);
                setCancellationReasons(response.data.result.cancellation_reasons);
                setOnLoad(1);
                if (response.data.result.trip.status == 5 && from == 'home') {
                    if (is_mount == 0) {
                        setIsMount(1);
                        navigation.dispatch(
                            CommonActions.reset({
                                index: 0,
                                routes: [{ name: "Bill", params: { trip_id: trip_id, from: from } }],
                            })
                        );
                    }
                } else if (cancellation_statuses.includes(parseInt(response.data.result.trip.status)) && from == 'home') {
                    navigate_home();
                }
            })
            .catch(error => {
                setLoading(false);
                console.log(error)
            });
    }

    const call_dialog_visible = () => {
        setDialogVisible(false)
    }

    call_driver = () => {
        Linking.openURL(`tel:${data.trip.driver.phone_number}`)
    }

   

    const call_trip_cancel = async (reason_id, type) => {
        setDialogVisible(false)

        await axios({
            method: 'post',
            url: api_url + trip_cancel,
            data: { trip_id: trip_id, status: 6, reason_id: reason_id, cancelled_by: type }
        })
            .then(async response => {
                setLoading(false);
                console.log('success')
                call_trip_details();
            })
            .catch(error => {
                //alert(error)
                setLoading(false);
            });
    }

    const navigate_home = () => {
        navigation.dispatch(
            CommonActions.reset({
                index: 0,
                routes: [{ name: "Home" }],
            })
        );
    }

    const move_chat = () => {
        navigation.navigate('Chat', { trip_id: trip_id });
    }

    const send_sos = async () => {
        Alert.alert(
            'Please confirm',
            'Are you in emergency ?',
            [
                {
                    text: 'Yes',
                    onPress: () => get_location()
                },
                {
                    text: 'No',
                    onPress: () => console.log("Cancel Pressed"),
                    style: "cancel"
                }
            ],
            { cancelable: false }
        );
    }

    const get_location = async () => {
        if (Platform.OS == "android") {
            await requestCameraPermission();
        } else {
            await getInitialLocation();
        }
    }

    const requestCameraPermission = async () => {
        try {
            const granted = await PermissionsAndroid.request(
                PermissionsAndroid.PERMISSIONS.ACCESS_FINE_LOCATION, {
                'title': 'Location access required',
                'message': { app_name } + 'Needs to access your location for tracking'
            }
            )
            if (granted === PermissionsAndroid.RESULTS.GRANTED) {
                await getInitialLocation();
            } else {
                alert('Sorry unable to fetch your location');
            }
        } catch (err) {
            alert('Sorry unable to fetch your location');
        }
    }

    const getInitialLocation = async () => {
        Geolocation.getCurrentPosition(async (position) => {
            call_sos_sms(position.coords.latitude, position.coords.longitude);
        }, error => console.log('Unable fetch your location'),
            { enableHighAccuracy: false, timeout: 10000 });
    }

    const call_sos_sms = (lat, lng) => {
        
        axios({
            method: 'post',
            url: api_url + sos_sms,
            data: { customer_id: global.id, booking_id: trip_id, latitude: lat, longitude: lng, lang: global.lang }
        })
            .then(async response => {
                setLoading(false);
                if (response.data.status == 1) {
                    alert(response.data.message);
                } if (response.data.status == 2) {
                    alert(response.data.message);
                } else {
                    Alert.alert(
                        strings.alert,
                        response.data.message,
                        [
                            {
                                text: 'Okay',
                                onPress: () => this.add_sos()
                            },
                            {
                                text: 'Cancel',
                                onPress: () => console.log("Cancel Pressed"),
                                style: "cancel"
                            }
                        ],
                        { cancelable: false }
                    );
                }
            })
            .catch(error => {
                setLoading(false);
                alert('Sorry something went wrong')
            });
    }

    const handleCancel = () => {
        setDialogVisible(false)
      }

    return (
        <View style={styles.container}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <MapView
                provider={PROVIDER_GOOGLE}
                ref={map_ref}
                style={styles.map}
                region={region}
                fitToElements={true}
            >

                {data.trip.status <= 2 &&
                    <Marker coordinate={home_marker}>
                        <Image style={{ height: 30, width: 25 }} source={require('.././assets/img/tracking/home.png')} />
                    </Marker>
                }
                {data.trip.status >= 2 &&
                    <Marker coordinate={destination_marker}>
                        <Image style={{ height: 30, width: 25 }} source={require('.././assets/img/tracking/destination.png')} />
                    </Marker>
                }
                <MarkerAnimated
                    ref={marker => {
                        setDriverTrack(marker);
                    }}
                    rotation={bearing}
                    coordinate={Platform.OS === "ios" ? driver_location_ios : driver_location}
                    identifier={'mk1'}
                >
                    {data.trip.vehicle_slug == 'car' &&
                        <Image style={{ height: 30, width: 15 }} source={require('.././assets/img/tracking/car.png')} />
                    }
                    {data.trip.vehicle_slug == 'bike' &&
                        <Image style={{ height: 30, width: 15 }} source={require('.././assets/img/tracking/bike.png')} />
                    }
                    {data.trip.vehicle_slug == 'truck' &&
                        <Image style={{ height:30, width:15 }} source={require('.././assets/img/tracking/truck.png')} />
                    }
                </MarkerAnimated>
                {global.polyline_status == 1 &&
                    <Polyline
                        coordinates={coords}
                        strokeWidth={4}
                        strokeColor={colors.theme_fg} />
                }
            </MapView>
            <DropdownAlert alert={func => (alt = func)} />
            <View style={{ flexDirection: 'row' }}>
                <DropShadow
                    style={{
                        width: '50%',
                        shadowColor: "#000",
                        shadowOffset: {
                            width: 0,
                            height: 0,
                        },
                        shadowOpacity: 0.3,
                        shadowRadius: 25,
                    }}
                >
                    <TouchableOpacity activeOpacity={0} onPress={go_back.bind(this)} style={{ width: 40, height: 40, backgroundColor: colors.theme_bg_three, borderRadius: 25, alignItems: 'center', justifyContent: 'center', top: 20, left: 20 }}>
                        <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.icon_active_color} style={{ fontSize: 22 }} />
                    </TouchableOpacity>
                </DropShadow>
                {on_load == 1 &&
                    <TouchableOpacity onPress={send_sos.bind(this)} activeOpacity={1} style={{ width: '50%', alignItems: 'flex-end' }}>
                        {drop_statuses.includes(data.trip.status) &&
                            <DropShadow
                                style={{
                                    shadowColor: "#000",
                                    shadowOffset: {
                                        width: 0,
                                        height: 0,
                                    },
                                    shadowOpacity: 0.3,
                                    shadowRadius: 25,
                                }}
                            >
                                <View style={{ width: 60, height: 60, backgroundColor: colors.theme_bg_three, borderRadius: 30, alignItems: 'center', justifyContent: 'center', top: 20, right: 20, borderWidth:3, borderColor: colors.error }}>
                                   {/*  <LottieView style={{flex: 1}} source={sos} autoPlay loop /> */}
                                   <Text style={{ color: colors.error, fontSize: 18, fontFamily: bold }}>SOS</Text>
                                </View>
                            </DropShadow>
                        }
                    </TouchableOpacity>
                }
            </View>
            <BottomSheet sliderMinHeight={400} sliderMaxHeight={screenHeight - 200} isOpen>
                {(onScrollEndDrag) => (
                    <ScrollView onScrollEndDrag={onScrollEndDrag} showsVerticalScrollIndicator={false}>
                        {on_load == 1 ?
                            <View style={{ padding: 10 }}>
                                <View>
                                    <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: 15, fontFamily: normal }}>Your OTP code is : #{data.trip.otp}</Text>
                                </View>
                                <View style={{ margin: 10 }} />
                                <View style={{ borderTopWidth: 0.5, borderBottomWidth: 0.5, borderColor: colors.grey }}>
                                    <View style={{ flexDirection: 'row', width: '100%', marginTop: 20, marginBottom: 20 }}>
                                        <View style={{ width: '25%', alignItems: 'center', justifyContent: 'center' }}>
                                            <View style={{ height: 50, width: 50 }} >
                                                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={{ uri: img_url + data.trip.driver.profile_picture }} />
                                            </View>
                                        </View>
                                        <View style={{ width: '40%', alignItems: 'flex-start', justifyContent: 'center' }}>
                                            <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: 17, fontFamily: bold }}>{data.trip.driver.first_name}</Text>
                                            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 5 }}>
                                                <Icon type={Icons.MaterialIcons} name="star" color={colors.warning} style={{ fontSize: 18 }} />
                                                <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 13, fontFamily: regular }}>{data.trip.driver.overall_ratings}</Text>
                                            </View>
                                        </View>
                                        <View style={{ width: '35%', alignItems: 'center', justifyContent: 'center' }}>
                                            <View style={{ height: 50, width: 50, marginBottom: 5 }} >
                                                <Image style={{ height: undefined, width: undefined, flex: 1, borderRadius: 5 }} source={{ uri: img_url + data.trip.vehicle.vehicle_image }} />
                                            </View>
                                            <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 13, fontFamily: regular }}>{data.trip.vehicle.vehicle_name} - {data.trip.vehicle.brand}</Text>
                                        </View>
                                    </View>
                                </View>
                                <View style={{ borderBottomWidth: 0.5, borderColor: colors.grey }}>
                                    <View style={{ flexDirection: 'row', width: '100%', marginTop: 10, marginBottom: 10 }}>
                                        <View style={{ width: '33%', alignItems: 'center', justifyContent: 'center' }}>
                                            <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 13, fontFamily: regular }}>Distance</Text>
                                            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 5 }}>
                                                <Icon type={Icons.MaterialIcons} name="map" color={colors.theme_fg_two} style={{ fontSize: 22 }} />
                                                <View style={{ margin: 2 }} />
                                                <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: 13, fontFamily: normal }}>{data.trip.distance} km</Text>
                                            </View>
                                        </View>
                                        <View style={{ width: '33%', alignItems: 'center', justifyContent: 'center' }}>
                                            <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 13, fontFamily: regular }}>Trip Type</Text>
                                            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 5 }}>
                                                <Icon type={Icons.MaterialIcons} name="commute" color={colors.theme_fg_two} style={{ fontSize: 22 }} />
                                                <View style={{ margin: 2 }} />
                                                <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: 13, fontFamily: normal }}>{data.trip.trip_type_name}</Text>
                                            </View>
                                        </View>
                                        <View style={{ width: '33%', alignItems: 'center', justifyContent: 'center' }}>
                                            <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 13, fontFamily: regular }}>Estimation Fare</Text>
                                            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 5 }}>
                                                <Icon type={Icons.MaterialIcons} name="local-atm" color={colors.theme_fg_two} style={{ fontSize: 22 }} />
                                                <View style={{ margin: 2 }} />
                                                <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: 13, fontFamily: normal }}>{global.currency}{data.trip.total}</Text>
                                            </View>
                                        </View>
                                    </View>
                                </View>
                                <View>
                                    <View style={{ width: '100%', marginTop: 20 }}>
                                        <TouchableOpacity activeOpacity={1} style={{ width: '100%', backgroundColor: colors.theme_bg_three }}>
                                            <View style={{ flexDirection: 'row', width: '100%', height: 50 }}>
                                                <View style={{ width: '10%', alignItems: 'center', justifyContent: 'flex-start', paddingTop: 4 }}>
                                                    <Badge status="success" backgroundColor="green"  size={10} />
                                                </View>
                                                <View style={{ margin: 3 }} />
                                                <View style={{ width: '90%', alignItems: 'flex-start', justifyContent: 'flex-start' }}>
                                                    <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 12, fontFamily: regular }}>Pickup Address</Text>
                                                    <View style={{ margin: 2 }} />
                                                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: 13, fontFamily: regular }}>{data.trip.pickup_address}</Text>
                                                </View>
                                            </View>
                                        </TouchableOpacity>
                                        {data.trip.trip_type != 2 &&
                                            <TouchableOpacity activeOpacity={1} style={{ width: '100%', backgroundColor: colors.theme_bg_three }}>
                                                <View style={{ flexDirection: 'row', width: '100%', height: 50 }}>
                                                    <View style={{ width: '10%', alignItems: 'center', justifyContent: 'flex-start', paddingTop: 4 }}>
                                                        <Badge status="error" backgroundColor="red"  size={10} />
                                                    </View>
                                                    <View style={{ margin: 3 }} />
                                                    <View style={{ width: '90%', alignItems: 'flex-start', justifyContent: 'flex-start' }}>
                                                        <Text numberOfLines={1} style={{ color: colors.grey, fontSize: 12, fontFamily: regular }}>Drop Address</Text>
                                                        <View style={{ margin: 2 }} />
                                                        <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: 13, fontFamily: regular }}>{data.trip.drop_address}</Text>
                                                    </View>
                                                </View>
                                            </TouchableOpacity>
                                        }
                                    </View>
                                </View>
                                {data.trip.status >= 1 && data.trip.status <= 5 &&
                                    <View style={{ width: '100%', marginBottom: 20 }}>
                                        {tip == 0 &&
                                            <View style={{ padding: 10 }}>
                                                <Text style={{ color: colors.theme_fg_two, fontSize: 20, fontFamily: bold }}>Add a tip for your driver</Text>
                                                <View style={{ margin: 2 }} />
                                                <Text style={{ color: colors.theme_fg_two, fontSize: 14, fontFamily: regular }}>The entire amount will be transferred to the rider. Valid only if you pay online.</Text>
                                                <View style={{ margin: 5 }} />
                                                <ScrollView horizontal={true} showsHorizontalScrollIndicator={false}>
                                                    <View style={{ flexDirection: 'row' }}>
                                                        {tips.map((row, index) => (
                                                            <TouchableOpacity onPress={call_add_tip.bind(this, row)} style={{ width: 60, margin: 5, height: 35, borderRadius: 10, borderColor: colors.theme_fg, borderWidth: 1, alignItems: 'center', justifyContent: 'center' }}>
                                                                <Text style={{ color: colors.theme_fg_two, fontSize: 14, fontFamily: bold }}>+{global.currency}{row}</Text>
                                                            </TouchableOpacity>
                                                        ))}
                                                    </View>
                                                </ScrollView>
                                            </View>
                                        }
                                    </View>
                                }
                                {pickup_statuses.includes(data.trip.status) &&
                                    <View style={{ borderTopWidth: 0, borderColor: colors.grey }}>
                                        <View style={{ flexDirection: 'row', width: '100%', marginBottom: 20 }}>
                                            <TouchableOpacity onPress={move_chat.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                                                <Icon type={Icons.MaterialIcons} name="chat" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                                            </TouchableOpacity>
                                            <View style={{ width: '5%' }} />
                                            <TouchableOpacity activeOpacity={1} onPress={call_driver.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                                                <Icon type={Icons.MaterialIcons} name="call" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                                            </TouchableOpacity>
                                            <View style={{ width: '10%' }} />
                                            {loading == false ?
                                                <TouchableOpacity onPress={showDialog.bind(this)} activeOpacity={1} style={{ width: '55%', backgroundColor: colors.error_background, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                                                    <Text style={{ color: colors.theme_fg_two, fontSize: 16, color: colors.error, fontFamily: bold }}>Cancel</Text>
                                                </TouchableOpacity>
                                                :
                                                <View style={{ height: 50, width: '90%', alignSelf: 'center' }}>
                                                    <LottieView style={{flex: 1}} source={loader} autoPlay loop />
                                                </View>
                                            }
                                        </View>
                                    </View>
                                }
                            </View>
                            :
                            <View style={{ alignItems: 'center', justifyContent: 'center', flex: 1 }}>
                                <Text style={{ color: colors.theme_fg_two, fontSize: 15, fontFamily: regular }}>Loading...</Text>
                            </View>
                        }
                    </ScrollView>
                )}
            </BottomSheet>
            <Dialog.Container
                visible={dialog_visible}
                
            >
                <Dialog.Title>Reason to cancel your ride.</Dialog.Title>
                <Dialog.Description>
                    <FlatList
                        data={cancellation_reason}
                        renderItem={({ item, index }) => (
                            <TouchableOpacity onPress={call_trip_cancel.bind(this, item.id, item.type)} activeOpacity={1} >
                                <View style={{ padding: 10 }}>
                                    <Text style={{ fontFamily: regular, fontSize: 12, color: colors.theme_fg_two }}>{item.reason}</Text>
                                </View>
                            </TouchableOpacity>
                        )}
                        keyExtractor={item => item.id}
                    />
                </Dialog.Description>
                <Dialog.Button label="Cancel" onPress={handleCancel} />
            </Dialog.Container>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        ...StyleSheet.absoluteFillObject,
        height: screenHeight,
        width: screenWidth,
        backgroundColor: colors.lite_bg
    },
    map: {
        ...StyleSheet.absoluteFillObject,
    },
});

function mapStateToProps(state) {
    return {
        initial_lat: state.booking.initial_lat,
        initial_lng: state.booking.initial_lng,
        initial_region: state.booking.initial_region,
    };
}

export default connect(mapStateToProps, null)(TripDetails);