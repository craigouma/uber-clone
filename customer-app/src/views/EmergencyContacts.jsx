//List
import React, { useState, useEffect, useRef } from "react";
import { useNavigation } from "@react-navigation/native";
import { StyleSheet, View, Text, Animated, TouchableOpacity, Image, FlatList } from 'react-native';
import { ScrollView } from '@gemcook/react-native-animated-scroll-view';
import * as colors from '../assets/css/Colors';
import Icon, { Icons } from '../components/Icons';
import { normal, bold, regular, logo, maxHeaderHeight, minHeaderHeight, api_url, sos_contact_list, delete_sos_contact, add_contact, loader, f_l, f_s, f_m, f_xl, f_30 } from '../config/Constants';
import axios from 'axios';
import LottieView from 'lottie-react-native';

const DATA = Array.from({ length: 5 }).map((_, index) => ({ id: index }));
const HEADER_SCROLL_DISTANCE = maxHeaderHeight - minHeaderHeight;

const EmergencyContacts = (props) => {
    const navigation = useNavigation();
    const animatedScrollYValue = useRef(new Animated.Value(0)).current;
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState("");

    const headerHeight = animatedScrollYValue.interpolate({
        inputRange: [120, HEADER_SCROLL_DISTANCE],
        outputRange: [0, 1],
        extrapolate: 'clamp',
    });

    const go_back = () => {
        navigation.goBack();
    }

    useEffect(() => {
        const unsubscribe = navigation.addListener("focus", async () => {
            call_sos_contact_list();
        });

        return (
            unsubscribe
        );
    }, []);

    const call_sos_contact_list = () => {
        setLoading(true);
        axios({
            method: 'post',
            url: api_url + sos_contact_list,
            data: { customer_id: global.id }
        })
        .then(async response => {
            setLoading(false);
            setData(response.data.result)
        })
        .catch(error => {
            setLoading(false);
            alert('Sorry something went wrong')
        });
    }

    const call_delete_sos_contact = (data) => {
        setLoading(true);
        axios({
            method: 'post',
            url: api_url + delete_sos_contact,
            data: { customer_id: global.id, contact_id: data.id }
        })
        .then(async response => {
            setLoading(false);
            if (response.data.status == 1) {
                alert(response.data.message);
                call_sos_contact_list();
            }
        })
        .catch(error => {
            setLoading(false);
            alert('Sorry something went wrong')
        });
    }

    const navigate_add_contact = () =>{
        navigation.navigate("AddEmergencyContact")
    }

    const animated_header = () => {
        return (
            <View style={styles.animationHeader}>
                <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                    <TouchableOpacity style={{ width: '30%', alignItems: 'flex-start', justifyContent: 'center', height: 60, paddingLeft: 17 }}>
                        <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                    </TouchableOpacity>
                </View>
                <View style={{ padding: 30, alignItems: 'center' }}>
                    <Text numberOfLines={2} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_30, letterSpacing: 1, fontFamily: bold, textAlign: 'center' }}>Setup Emergency Contacts</Text>
                </View>
            </View>
        )
    }

    const show_list = ({ item }) => (
        <TouchableOpacity style={{ flexDirection: 'row', padding: 10 }}>
            <View style={{ width: '20%', alignItems: 'center', justifyContent: 'center' }}>
                <View style={{ width: 50, height: 50 }} >
                    <Image style={{ height: undefined, width: undefined, flex: 1, borderRadius: 25 }} source={logo} />
                </View>
            </View>
            <View style={{ width: '80%', borderBottomWidth: 0.5, borderColor: colors.grey, flexDirection: 'row', alignItems: 'center', justifyContent: 'center', paddingBottom: 10 }}>
                <View style={{ width: '75%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text style={{ color: colors.text_grey, fontSize: f_l, fontFamily: normal }}>{item.name}</Text>
                    <View style={{ margin: 3 }} />
                    <Text style={{ color: colors.text_grey, fontSize: f_s, fontFamily: regular, letterSpacing: 1 }}>{item.phone_number}</Text>
                </View>
                <TouchableOpacity onPress={call_delete_sos_contact.bind(this, item)} activeOpacity={1} style={{ width: '25%', alignItems: 'flex-end', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="delete" color={colors.icon_inactive_color} style={{ fontSize: 22 }} />
                </TouchableOpacity>
            </View>
        </TouchableOpacity>
    );

    return (
        <View style={styles.container}>
            <ScrollView
                maxHeaderHeight={maxHeaderHeight}
                minHeaderHeight={minHeaderHeight}
                onScroll={Animated.event([{ nativeEvent: { contentOffset: { y: animatedScrollYValue } } }])}
                AnimationHeaderComponent={
                    animated_header()
                }
            >
                <View style={{ margin: 5 }} />
                {data.length > 0 ?
                    <FlatList
                        data={data}
                        renderItem={show_list}
                        keyExtractor={item => item.id}
                    />
                :
                <TouchableOpacity onPress={navigate_add_contact.bind(this)} style={{ justifyContent:'center', alignItems:'center', marginTop:'20%' }}>
                    <View style={{ height: 200, width: 250}}>
                        <Image source={add_contact} style={{ height:undefined, width:undefined, flex:1 }} />
                    </View>
                    <View style={{ margin:10 }} />
                    <Text numberOfLines={1} style={{ color: colors.text_grey, fontSize: f_s, fontFamily:normal }}>Add your contact</Text>
                </TouchableOpacity>
                }
                {loading == true &&
                    <View style={{ height: 100, width: '90%', alignSelf: 'center', justifyContent:'center' }}>
                        <LottieView style={{flex: 1}} source={loader} autoPlay loop />
                    </View>
                }
            </ScrollView>
            <View style={{ padding:10 }}>
                <TouchableOpacity onPress={ navigate_add_contact.bind(this) } activeOpacity={1} style={{ width: '100%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row',  alignItems: 'center', justifyContent: 'center' }}>
                    <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Add Contacts</Text>
                </TouchableOpacity>
            </View>
            
            <Animated.View opacity={headerHeight} style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                </TouchableOpacity>
                <View style={{ margin: '2%' }} />
                <TouchableOpacity activeOpacity={1} style={{ width: '83%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>Emergency Contacts</Text>
                </TouchableOpacity>
            </Animated.View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
    },
    animationHeader: {
        backgroundColor: colors.theme_bg,
        height: '100%',
        width: '100%',
    },
    header: {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        zIndex: 16,
        height: 60,
        backgroundColor: colors.theme_bg,
        overflow: 'hidden',
        flexDirection: 'row',
        alignItems: 'center'
    },
    title: {
        backgroundColor: 'transparent',
        color: 'white',
        fontSize: 18,
    },
});

export default EmergencyContacts;