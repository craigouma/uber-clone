import React, { useState, useEffect, useRef } from "react";
import { useNavigation, useRoute } from "@react-navigation/native";
import { StyleSheet, View, Text, Animated, TouchableOpacity, Image, StatusBar, FlatList } from 'react-native';
import { ScrollView } from '@gemcook/react-native-animated-scroll-view';
import * as colors from '../assets/css/Colors';
import Icon, { Icons } from '../components/Icons';
import { normal, bold, api_url, complaint_categories, maxHeaderHeight, minHeaderHeight, loader, f_s, f_xl, f_30 } from '../config/Constants';
import axios from 'axios';
import LottieView from 'lottie-react-native';

const DATA = Array.from({ length: 30 }).map((_, index) => ({ id: index }));
const HEADER_SCROLL_DISTANCE = maxHeaderHeight - minHeaderHeight;

const ComplaintCategory = (props) => {
    const navigation = useNavigation();
    const route = useRoute();
    const animatedScrollYValue = useRef(new Animated.Value(0)).current;
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState("");
    const [trip_id, setTripId] = useState(route.params.trip_id);
    

    const headerHeight = animatedScrollYValue.interpolate({
        inputRange: [120, HEADER_SCROLL_DISTANCE],
        outputRange: [0, 1],
        extrapolate: 'clamp',
    });

    const go_back = () => {
        navigation.goBack();
    }

    useEffect(() => {
        call_complaint_categories();
    }, []);

    const call_complaint_categories = () => {
        setLoading(true);
        axios({
            method: 'post',
            url: api_url + complaint_categories,
            data: { lang: global.lang }
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

    const navigate_complaint_sub_category = (id, complaint_category_name, trip_id) => {
        navigation.navigate("ComplaintSubCategory", { complaint_category_id: id, complaint_category_name: complaint_category_name, trip_id: trip_id })
    }

    const animated_header = () => {
        return (
            <View style={styles.animationHeader}>
                <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                    <TouchableOpacity style={{ width: '30%', alignItems: 'flex-start', justifyContent: 'center', height: 60, paddingLeft: 17 }}>
                        <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                    </TouchableOpacity>
                </View>
                <View style={{ padding: 10, flexDirection: 'row' }}>
                    <View style={{ width: '70%', alignItems: 'flex-start', justifyContent: 'center' }}>
                        <Text numberOfLines={2} ellipsizeMode='tail' style={{ paddingLeft: 17, color: colors.theme_fg_three, fontSize: f_30, letterSpacing: 1, fontFamily: bold }}>How can we help you?</Text>
                    </View>
                    <View style={{ width: '30%', alignItems: 'flex-start', justifyContent: 'center' }}>
                        <View style={{ height: 100, width: 100 }} >
                            <Image style={{ height: undefined, width: undefined, flex: 1 }} source={require(".././assets/img/help-desk.png")} />
                        </View>
                    </View>
                </View>
            </View>
        )
    }

    const show_list = ({ item }) => (
        <TouchableOpacity onPress={navigate_complaint_sub_category.bind(this, item.id, item.complaint_category_name, trip_id)} style={{ flexDirection: 'row', padding: 20 }}>
            <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                <Icon type={Icons.MaterialIcons} name="notes" color={colors.icon_inactive_color} style={{ fontSize: 22 }} />
            </View>
            <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: normal }}>{item.complaint_category_name}</Text>
            </View>
        </TouchableOpacity>
    );
    return (
        <View style={styles.container}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <ScrollView
                maxHeaderHeight={maxHeaderHeight}
                minHeaderHeight={minHeaderHeight}
                onScroll={Animated.event([{ nativeEvent: { contentOffset: { y: animatedScrollYValue } } }])}
                AnimationHeaderComponent={
                    animated_header()
                }
            >
                <View style={{ margin: 5 }} />
                {loading == true ?
                    <View style={{ height: 100, width: 100, alignSelf: 'center', marginTop: '30%' }}>
                        <LottieView style={{flex: 1}}source={loader} autoPlay loop />
                    </View>
                    :
                    <FlatList
                        data={data}
                        renderItem={show_list}
                        keyExtractor={item => item.id}
                    />
                }
            </ScrollView>
            <Animated.View opacity={headerHeight} style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                </TouchableOpacity>
                <View style={{ margin: '2%' }} />
                <TouchableOpacity style={{ width: '83%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>Select the reason</Text>
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

export default ComplaintCategory;