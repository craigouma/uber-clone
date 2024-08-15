import React, { useState, useRef } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    SafeAreaView,
    StatusBar,
    ScrollView,
    TextInput
} from "react-native";
import { useNavigation, useRoute } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth, api_url, add_complaint, bold, regular, btn_loader, f_xl, f_m, f_s } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';
import DropShadow from "react-native-drop-shadow";
import LottieView from 'lottie-react-native';
import axios from 'axios';
import DropdownAlert, {
    DropdownAlertData,
    DropdownAlertType,
  } from 'react-native-dropdownalert';

const CreateComplaint = (props) => {
    const navigation = useNavigation();
    const route = useRoute();
    const [loading, setLoading] = useState(false);
    const [subject, setSubject] = useState("");
    const [description, setDescription] = useState("");
    const [trip_id, setTripId] = useState(route.params.trip_id);
    const [complaint_category_id, setComplaintCategoryId] = useState(route.params.complaint_category_id);
    const [complaint_sub_category_id, setComplaintSubCategoryId] = useState(route.params.complaint_sub_category_id);
    const [complaint_category_name, setComplaintCategoryName] = useState(route.params.complaint_category_name);
    const [sub_category_data, setSubCategoryData] = useState(route.params.sub_category_data);

    let alt = useRef(
        (_data?: DropdownAlertData) => new Promise<DropdownAlertData>(res => res),
    );
    
    const inputRef = useRef();
    const go_back = () => {
        navigation.goBack();
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
    const call_validation = () => {
        if(subject == "" || description == ""){
            alt({
                type: DropdownAlertType.Error,
                title: 'Validation error',
                message: 'Please enter all the required fields',
              });
            
        }else{
            call_add_complaint();
        }
    }

    const call_add_complaint = () => {
        axios({
            method: 'post',
            url: api_url + add_complaint,
            data: { trip_id:trip_id, complaint_category:complaint_category_id, complaint_sub_category:complaint_sub_category_id, subject:subject, description:description }
        })
            .then(async response => {
                setLoading(false);
                if(response.data.status == 1){
                    navigate_bill();
                    alt({
                        type: DropdownAlertType.Success,
                        title: 'Registered success',
                        message: 'Your complaint registered successfully.',
                      });
                    
                }else{
                    alt({
                        type: DropdownAlertType.Error,
                        title: 'Validation error',
                        message: 'Sorry something went wrong',
                      });
                    
                }
            })
            .catch(error => {
                setLoading(false);
                alt({
                    type: DropdownAlertType.Error,
                    title: 'Validation error',
                    message: 'Sorry something went wrong',
                  });
                
            });
    }

    const navigate_bill = () => {
        navigation.navigate("Bill",{trip_id: trip_id})
    }

    return (
        <SafeAreaView style={{ backgroundColor: colors.lite_bg, flex: 1 }}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <View style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                </TouchableOpacity>
                <View activeOpacity={1} style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>Complaint</Text>
                </View>
            </View>
            <ScrollView>
                <View style={{ alignItems: 'center'}}>
                    <DropShadow
                        style={{
                            width: '95%',
                            marginBottom: 5,
                            marginTop: 5,
                            shadowColor: "#000",
                            shadowOffset: {
                                width: 0,
                                height: 0,
                            },
                            shadowOpacity: 0.1,
                            shadowRadius: 5,
                        }}
                    >
                        <View style={{ margin:2.5}} />
                        <View style={{ width: '100%', backgroundColor: colors.theme_bg_three, borderRadius: 10, padding: 20, marginTop:5, marginBottom:5 }}>
                            <View style={{ width:'100%', alignItems:'flex-start', justifyContent:'center'}}>
                                <Text style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily:bold }}>{complaint_category_name} / {sub_category_data.complaint_sub_category_name}</Text>
                                <View style={{ margin:3 }} />
                                <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily:regular }}>{sub_category_data.short_description}</Text>
                            </View>
                        </View>
                        <View style={{ width: '100%', backgroundColor: colors.theme_bg_three, borderRadius: 10, padding: 20, marginTop:5, marginBottom:5 }}>
                            <View style={{ width: '100%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg, borderRadius:10, borderWidth:1, borderStyle: 'dotted' }}>
                                <TextInput
                                    ref={inputRef}
                                    placeholder="Subject"
                                    secureTextEntry={false}
                                    placeholderTextColor={colors.grey}
                                    style={styles.textinput}
                                    onChangeText={TextInputValue =>
                                        setSubject(TextInputValue)}
                                />
                            </View>
                            <View style={{ margin: 10 }} />
                            <View style={{ width: '100%', alignItems: 'flex-start', paddingLeft: 10, justifyContent: 'center', backgroundColor: colors.text_container_bg, borderRadius:10, borderWidth:1, borderStyle: 'dotted' }}>
                                <TextInput
                                    placeholder="Enter details about your complaint"
                                    secureTextEntry={false}
                                    multiline={true}
                                    numberOfLines={10}
                                    placeholderTextColor={colors.grey}
                                    style={styles.textarea}
                                    onChangeText={TextInputValue =>
                                        setDescription(TextInputValue)}
                                />
                            </View>
                            <View style={{ margin: 10 }} />
                            {loading == true ?
                                <View style={{ height: 50, width: '90%', alignSelf: 'center' }}>
                                <LottieView style={{flex: 1}} source={btn_loader} autoPlay loop />
                            </View>
                            :
                                <TouchableOpacity onPress={call_validation.bind(this)} activeOpacity={1} style={{ width: '100%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                                    <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Submit</Text>
                                </TouchableOpacity>
                            }
                        </View>
                    </DropShadow>
                </View>
            </ScrollView>
            <DropdownAlert alert={func => (alt = func)} />
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    container: {
        ...StyleSheet.absoluteFillObject,
        height: screenHeight,
        width: screenWidth,
        backgroundColor: colors.lite_bg
    },
    header: {
        height: 60,
        backgroundColor: colors.theme_bg,
        flexDirection: 'row',
        alignItems: 'center'
    },
    textinput: {
        fontSize: f_m,
        color: colors.grey,
        fontFamily: regular,
        height: 60,
        borderRadius:10,
        backgroundColor: colors.text_container_bg,
        width: '100%'
    },
    textarea: {
        fontSize: f_m,
        color: colors.grey,
        fontFamily: regular,
        borderRadius:10,
        textAlignVertical: 'top',
        backgroundColor: colors.text_container_bg,
        width: '100%'
    },
});

export default CreateComplaint;