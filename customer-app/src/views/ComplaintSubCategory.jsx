import React, { useState, useEffect, useRef } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    SafeAreaView,
    StatusBar,
    FlatList
} from "react-native";
import { useNavigation, useRoute } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth, bold, regular, complaint_sub_categories, api_url, loader, f_s, f_xl } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';
import axios from 'axios';
import Animated, {useAnimatedStyle, withTiming, useSharedValue } from 'react-native-reanimated';

const ComplaintSubCategory = (props) => {
    const navigation = useNavigation();
    const route = useRoute();
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState("");
    const [trip_id, setTripId] = useState(route.params.trip_id);
    const [complaint_category_id, setComplaintCategoryId] = useState(route.params.complaint_category_id);
    const [complaint_category_name, setComplaintCategoryName] = useState(route.params.complaint_category_name);
    const viewableItems = useSharedValue([]);

    const go_back = () => {
        navigation.goBack();
    }

    useEffect(() => {
        call_complaint_sub_categories();
    }, []);

    const call_complaint_sub_categories = () => {
        setLoading(true);
        axios({
            method: 'post',
            url: api_url + complaint_sub_categories,
            data: { lang: global.lang, complaint_category_id:complaint_category_id }
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

    const navigate_complaint = (complaint_sub_category_id, sub_category_data) => {
        navigation.navigate("CreateComplaint", {trip_id:trip_id, 
            complaint_category_id: complaint_category_id, 
            complaint_sub_category_id: complaint_sub_category_id, 
            complaint_category_name: complaint_category_name,
            sub_category_data: sub_category_data })
    }

    const show_list = ({ item }) => (
        <TouchableOpacity activeOpacity={1} onPress={navigate_complaint.bind(this, item.id, item)} style={{ backgroundColor: colors.theme_bg_three, padding: 10, marginLeft: 10, marginRight: 10, marginTop:5, marginBottom:5, borderRadius: 10 }}>
            <View style={{ margin: 10 }}>
                <Text ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily:bold }}>{item.complaint_sub_category_name}</Text>
                <View style={{ margin:3 }} />
                <Text numberOfLines={1} style={{ color: colors.grey, fontSize: f_s, fontFamily: regular }}>
                    {item.short_description} 
                </Text>
            </View>
        </TouchableOpacity>
    );

    type ListItemProps = {
        viewableItems: Animated.SharedValue<ViewToken[]>;
        item: {
            id: number;
        };
    };
    
    const ListItem: React.FC<ListItemProps> = React.memo(
        ({ item, viewableItems }) => {
        const rStyle = useAnimatedStyle(() => {
            const isVisible = Boolean(
            viewableItems.value
                .filter((item) => item.isViewable)
                .find((viewableItem) => viewableItem.item.id === item.id)
            );
            return {
            opacity: withTiming(isVisible ? 1 : 0),
            transform: [
                {
                scale: withTiming(isVisible ? 1 : 0.6),
                },
            ],
            };
        }, []);
        return (
            <Animated.View style={[
                {
                  width: '100%',
                },
                rStyle,
              ]}>
                <TouchableOpacity activeOpacity={1} onPress={navigate_complaint.bind(this, item.id, item)} style={{ backgroundColor: colors.theme_bg_three, padding: 10, marginLeft: 10, marginRight: 10, marginTop:5, marginBottom:5, borderRadius: 10 }}>
                    <View style={{ margin: 10 }}>
                        <Text ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily:bold }}>{item.complaint_sub_category_name}</Text>
                        <View style={{ margin:3 }} />
                        <Text numberOfLines={1} style={{ color: colors.grey, fontSize: f_s, fontFamily: regular }}>
                            {item.short_description} 
                        </Text>
                    </View>
                </TouchableOpacity>
            </Animated.View>
        );
        }
    );
    
    const onViewableItemsChanged = ({viewableItems: vItems}) => {
        viewableItems.value = vItems;
    };
    
    const viewabilityConfigCallbackPairs = useRef([{onViewableItemsChanged}]);

    return (
        <SafeAreaView style={styles.container}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <View style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                </TouchableOpacity>
                <View activeOpacity={1} style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>{complaint_category_name}</Text>
                </View>
            </View>
            <FlatList
                data={data}
                contentContainerStyle={{ paddingTop: 10 }}
                viewabilityConfigCallbackPairs={
                    viewabilityConfigCallbackPairs.current
                }
                renderItem={({ item }) => {
                    return <ListItem item={item} viewableItems={viewableItems} />;
                }}
            />
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    container: {
        ...StyleSheet.absoluteFillObject,
        height: screenHeight,
        width: screenWidth,
        backgroundColor: colors.theme
    },
    header: {
        height: 60,
        backgroundColor: colors.theme_bg,
        flexDirection: 'row',
        alignItems: 'center'
    },
});

export default ComplaintSubCategory;