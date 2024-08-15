import React, { useState, useRef } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    SafeAreaView,
    TextInput,
    StatusBar
} from "react-native";
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { normal, bold, regular, add_sos_contact, api_url, btn_loader, f_xl, f_xs, f_m } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';
import DropdownAlert, {
    DropdownAlertData,
    DropdownAlertType,
  } from 'react-native-dropdownalert';
import LottieView from 'lottie-react-native';
import axios from 'axios';

const AddEmergencyContact = (props) => {
    const navigation = useNavigation();
    const [contact_name, setContactName] = useState('');
    const [formattedValue, setFormattedValue] = useState("");
    const [value, setValue] = useState("");
    const [contact_number, setContactNumber] = useState(""); 
    const [loading, setLoading] = useState(false);
    const [phone_number_validation, setPhoneNumberValidation] = useState(false);
    let alt = useRef(
        (_data?: DropdownAlertData) => new Promise<DropdownAlertData>(res => res),
    );
    const inputRef = useRef();   
    const phoneInput = useRef();

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

    const go_back = () => {
        navigation.goBack();
    }


    const check_valid = async () => {
        if (contact_name != '' && contact_number != '') {
            call_add_sos_contact();
        } else {
            alt({
                type: DropdownAlertType.Error,
                title: 'Validation error',
                message: 'Please enter all the required fields',
              });
        }
    }

    /* const check_phone_number = (contact_number) => {
        if(contact_number != ''){
            const reg = /^\d{10}$/;
            if (reg.test(contact_number) === false) {
            setPhoneNumberValidation(false);
            message: 'Enter valid phone number'
            return false;
            } else {
                setPhoneNumberValidation(true);
                setContactNumber(contact_number)
            return true;
            check_valid();
            }
        }else{
            alert('Enter your contact number')
        }
      } */

    const call_add_sos_contact = async () => {      
        axios({
            method: 'post',
            url: api_url + add_sos_contact,
            data: { customer_id: global.id, name: contact_name, phone_number:contact_number }
        })
        .then(async response => {
            setLoading(false);
            if(response.data.status == 1){
                go_back();
                alt({
                    type: DropdownAlertType.Success,
                    title: 'Your emergency contact added successfully',
                    message: response.data.message ,
                  });
                
            }
        })
        .catch(error => {
            setLoading(false);
            alert('Sorry something went wrong')
        });
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
                <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: f_xl, fontFamily: bold }}>Add Emergency Contact</Text>
                <View style={{ margin: 5 }} />
                <Text numberOfLines={1} style={{ color: colors.grey, fontSize: f_xs, fontFamily: normal }}>Add emergency contact name and number</Text>
                <View style={{ margin: 20 }} />
                <View style={{ width: '80%' }}>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ width: '25%', alignItems: 'center', justifyContent: 'center', backgroundColor: colors.theme_bg_three }}>
                            <Icon type={Icons.MaterialIcons} name="contact-phone" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                        </View>
                        <View style={{ width: '75%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg }}>
                            <TextInput
                                placeholder="Contact Number"
                                keyboardType="numeric"
                                secureTextEntry={false}
                                placeholderTextColor={colors.grey}
                                style={styles.textinput}
                                onChangeText={TextInputValue =>
                                    setContactNumber(TextInputValue)}
                            />
                        </View>
                    </View>
                    <View style={{ margin: 10 }} />
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ width: '25%', alignItems: 'center', justifyContent: 'center', backgroundColor: colors.theme_bg_three }}>
                            <Icon type={Icons.MaterialIcons} name="badge" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                        </View>
                        <View style={{ width: '75%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg }}>
                            <TextInput
                                placeholder="Contact Name"
                                secureTextEntry={false}
                                placeholderTextColor={colors.grey}
                                style={styles.textinput}
                                onChangeText={TextInputValue =>
                                    setContactName(TextInputValue)}
                            />
                        </View>
                    </View>
                    <View style={{ margin: 30 }} />
                    {loading == false ?
                        <TouchableOpacity onPress={check_valid.bind(this)} activeOpacity={1} style={{ width: '100%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                            <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Add</Text>
                        </TouchableOpacity>
                        :
                        <View style={{ height: 50, width: '90%', alignSelf: 'center' }}>
                            <LottieView style={{flex: 1}} source={btn_loader} autoPlay loop />
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

export default AddEmergencyContact;