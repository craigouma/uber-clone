//List
import React, { useState, useEffect, useRef } from "react";
import { useNavigation } from "@react-navigation/native";
import { StyleSheet, View, Text, TouchableOpacity, FlatList, StatusBar, ViewToken } from 'react-native';
import * as colors from '../assets/css/Colors';
import Icon, { Icons } from '../components/Icons';
import { normal, bold, api_url, faq, maxHeaderHeight, minHeaderHeight, f_s, f_xl, f_30 } from '../config/Constants';
import axios from 'axios';
import Animated, { useAnimatedStyle, withTiming, useSharedValue } from 'react-native-reanimated';

const Faq = (props) => {
    const navigation = useNavigation();
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState("");
    const viewableItems = useSharedValue([]);

    const go_back = () => {
        navigation.goBack();
    }

    useEffect(() => {
        call_faq();
    }, []);

    const call_faq = () => {
        setLoading(true);
        axios({
            method: 'post',
            url: api_url + faq,
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

    navigate_faq_details = (data) => {
        navigation.navigate('FaqDetails', { data: data });
    }

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
                    <TouchableOpacity activeOpacity={1} onPress={navigate_faq_details.bind(this, item)} style={{ flexDirection: 'row', padding: 20 }}>
                        <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                            <Icon type={Icons.MaterialIcons} name="notes" color={colors.icon_inactive_color} style={{ fontSize: 22 }} />
                        </View>
                        <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                            <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: normal }}>{item.question}</Text>
                        </View>
                    </TouchableOpacity>
                </Animated.View>
            );
        }
    );

    const onViewableItemsChanged = ({ viewableItems: vItems }) => {
        viewableItems.value = vItems;
    };

    const viewabilityConfigCallbackPairs = useRef([{ onViewableItemsChanged }]);

    return (
        <View style={styles.container}>
            <View style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
                </TouchableOpacity>
                <View activeOpacity={1} style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                    <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>Faq</Text>
                </View>
            </View>
            <FlatList
                data={data}
                contentContainerStyle={{ paddingTop: 20 }}
                viewabilityConfigCallbackPairs={
                    viewabilityConfigCallbackPairs.current
                }
                renderItem={({ item }) => {
                    return <ListItem item={item} viewableItems={viewableItems} />;
                }}
            />
        </View>
    );
};

const styles = StyleSheet.create({
    header: {
        height: 60,
        backgroundColor: colors.theme_bg,
        flexDirection: 'row',
        alignItems: 'center'
    },
    container: {
        flex: 1,
    },
    title: {
        backgroundColor: 'transparent',
        color: 'white',
        fontSize: 18,
    },
});

export default Faq;