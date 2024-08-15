import React, { useState, useEffect, useRef } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    SafeAreaView,
    TextInput,
    Keyboard,
    StatusBar,
} from "react-native";
import { useNavigation, CommonActions } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { api_url, register, normal, bold, regular, f_xl, f_xs, f_m , btn_loader} from '../config/Constants';
import Icon, { Icons } from '../components/Icons';
import DropdownAlert, {
    DropdownAlertData,
    DropdownAlertType,
  } from 'react-native-dropdownalert';
import { connect } from 'react-redux';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import LottieView from 'lottie-react-native';

const CreatePassword = (props) => {
    const navigation = useNavigation();
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [confirm_password, setConfirmPassword] = useState('');
    let alt = useRef(
        (_data?: DropdownAlertData) => new Promise<DropdownAlertData>(res => res),
    );
    const inputRef = useRef();

    const go_back = () => {
        navigation.goBack();
    }

    useEffect(() => {
        setTimeout(() => inputRef.current.focus(), 100)
    }, []);

    axios.interceptors.request.use(async function (config) {
        // Do something before request is sent
        //console.log("loading")
        setLoading(true);
        return config;
    }, function (error) {
          //console.log(error)
          setLoading(false);
          console.log("finish loading")
          // Do something with request error
        return Promise.reject(error);
    });
    const check_valid = () => {
        if (password) {
            check_password();
        } else {
            alt({
                type: DropdownAlertType.Error,
                title: 'Validation Error',
                message: 'Please enter your password',
              });
            
        }
    }

    const check_password = () => {
        if (password == confirm_password) {
            //navigation.navigate('Home');
            call_register();
        } else {
            alt({
                type: DropdownAlertType.Error,
                title: 'Validation Error',
                message: 'Your password and confirm password did not match',
              });
        }
    }
    
    const call_register = async () => { 
        Keyboard.dismiss();
        await axios({
            method: 'post',
            url: api_url + register,
            data: { fcm_token: global.fcm_token, phone_number: props.phone_number, phone_with_code: props.phone_with_code, country_code: props.country_code, first_name: props.first_name, last_name: props.last_name, email: props.email, password: password, referral_code: '' }
        })
            .then(async response => {   
                
                setLoading(false);
                if (response.data.status == 1) {
                    save_data(response.data.result, response.data.status, response.data.message);
                } else { 
                    alt({
                        type: DropdownAlertType.Error,
                        title: 'Validation Error',
                        message: response.data.message,
                      });
                    
                }
            })
            .catch(error => {
                setLoading(false);
                alt({
                    type: DropdownAlertType.Error,
                    title: 'Error',
                    message: 'Sorry something went wrong',
                  });
                
            });
    }

    const save_data = async (data, status, message) => {
        console.log(JSON.stringify(data))

        if (status == 1) {
            try {
                await AsyncStorage.setItem('id', data.id.toString());
                await AsyncStorage.setItem('first_name', data.first_name.toString());
                await AsyncStorage.setItem('profile_picture', data.profile_picture.toString());
                await AsyncStorage.setItem('phone_with_code', data.phone_with_code.toString());
                await AsyncStorage.setItem('email', data.email.toString());
                global.id = await data.id;
                global.first_name = await data.first_name;
                global.phone_with_code = await data.phone_with_code;
                global.email = await data.email;
                global.profile_picture = await data.profile_picture;
               
                await navigate();

            } catch (e) {
                alt({
                    type: DropdownAlertType.Error,
                    title: 'Error',
                    message: 'Sorry something went wrong',
                  });
                
            }
        } else {
            alt({
                type: DropdownAlertType.Error,
                title: 'Error',
                message: message,
              });
            
        }
    }

    const navigate = async () => {
        console.log(global.id+'create_password')
        navigation.dispatch(
            CommonActions.reset({
                index: 0,
                routes: [{ name: "Home" }],
            })
        );
    }

   

    return (
        <SafeAreaView style={{ backgroundColor: colors.lite_bg, flex: 1 }}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <View style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                </TouchableOpacity>
            </View>
            <View style={{ margin: 20 }} />
            <View style={{ alignItems: 'center', justifyContent: 'center' }}>
                <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: f_xl, fontFamily: bold }}>Create your password</Text>
                <View style={{ margin: 5 }} />
                <Text numberOfLines={1} style={{ color: colors.grey, fontSize: f_xs, fontFamily: normal }}>Create your new password</Text>
                <View style={{ margin: 20 }} />
                <View style={{ width: '80%' }}>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ width: '25%', alignItems: 'center', justifyContent: 'center', backgroundColor: colors.theme_bg_three }}>
                            <Icon type={Icons.MaterialIcons} name="lock" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                        </View>
                        <View style={{ width: '75%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg }}>
                            <TextInput
                                ref={inputRef}
                                placeholder="Password"
                                secureTextEntry={true}
                                placeholderTextColor={colors.grey}
                                style={styles.textinput}
                                onChangeText={TextInputValue =>
                                    setPassword(TextInputValue)}
                            />
                        </View>
                    </View>
                    <View style={{ margin: 10 }} />
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ width: '25%', alignItems: 'center', justifyContent: 'center', backgroundColor: colors.theme_bg_three }}>
                            <Icon type={Icons.MaterialIcons} name="lock" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                        </View>
                        <View style={{ width: '75%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg }}>
                            <TextInput
                                placeholder="Confirm Password"
                                secureTextEntry={true}
                                placeholderTextColor={colors.grey}
                                style={styles.textinput}
                                onChangeText={TextInputValue =>
                                    setConfirmPassword(TextInputValue)}
                            />
                        </View>
                    </View>
                    <View style={{ margin: 30 }} />
                    {loading == false ?
                        <TouchableOpacity onPress={check_valid.bind(this)} activeOpacity={1} style={{ width: '100%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                            <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Register</Text>
                        </TouchableOpacity>
                        :
                        <View style={{ height: 50, width: '90%', alignSelf: 'center' }}>
                            <LottieView style={{flex: 1}}source={btn_loader} autoPlay loop />
                        </View>
                    }

                </View>
            </View>
            <DropdownAlert alert={func => (alt = func)} />
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    header: {
        height: 60,
        backgroundColor: colors.lite_bg,
        flexDirection: 'row',
        alignItems: 'center'
    },
    textinput: {
        fontSize: f_m,
        color: colors.grey,
        fontFamily: regular,
        height: 60,
        backgroundColor: colors.text_container_bg,
        width: '100%'
    },
});

function mapStateToProps(state) {
    return {
        phone_number: state.register.phone_number,
        phone_with_code: state.register.phone_with_code,
        country_code: state.register.country_code,
        first_name: state.register.first_name,
        last_name: state.register.last_name,
        email: state.register.email,
    };
}

export default connect(mapStateToProps, null)(CreatePassword);