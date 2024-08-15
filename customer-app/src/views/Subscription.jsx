import React, { useState } from "react";
import {
    TouchableOpacity,
    Text,
    StyleSheet,
    View,
    SafeAreaView,
    ScrollView,
    Image,
    StatusBar
} from "react-native";
import { Switch } from 'react-native-switch';
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import Icon, { Icons } from '../components/Icons';
import { normal, bold, check_icon, f_s, f_30, f_xs, f_m } from '../config/Constants';
const Subscription = (props) => {
    const navigation = useNavigation();
    const [is_enabled, setEnabled] = useState(true);

    const go_back = () => {
        navigation.goBack();
    }

    const toggle_switch = (val) => {
        setEnabled(val)
    }

    return (
        <SafeAreaView style={{ backgroundColor: colors.theme_bg_three, flex: 1 }}>
            <StatusBar
                backgroundColor={colors.theme_bg}
            />
            <View style={[styles.header]}>
                <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                    <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
                </TouchableOpacity>
            </View>
            <ScrollView>
                <View style={{ padding: 20 }}>
                    <View style={{ alignItems: 'center', justifyContent: 'center' }}>
                        <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg, fontSize: f_s, fontFamily: bold }}>Get more benefits</Text>
                        <View style={{ margin: 3 }} />
                        <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_30, fontFamily: bold }}>Pro Membership</Text>
                        <View style={{ margin: 5 }} />
                        <Text style={{ color: colors.text_grey, fontSize: f_xs, fontFamily: normal, textAlign: 'center' }}>Join Today to our pro membership and get more benefits like free rides, discounts, exclusive offers like that</Text>
                    </View>
                    <View style={{ margin: 20 }} />
                    <View style={{ alignItems: 'center', justifyContent: 'center', flexDirection: 'row' }}>
                        <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: normal }}>Monthly</Text>
                        <View style={{ margin: 10 }} />
                        <Switch
                            value={is_enabled}
                            onValueChange={(val) => toggle_switch(val)}
                            disabled={false}
                            activeText={'Annually'}
                            inActiveText={'Monthly'}
                            circleSize={30}
                            barHeight={30}
                            circleBorderWidth={3}
                            backgroundActive={colors.theme_bg}
                            backgroundInactive={colors.grey}
                            circleActiveColor={colors.theme_fg_three}
                            circleInActiveColor={colors.theme_fg_two}
                            changeValueImmediately={true} // if rendering inside circle, change state immediately or wait for animation to complete
                            innerCircleStyle={{ alignItems: "center", justifyContent: "center" }} // style for inner animated circle for what you (may) be rendering inside the circle
                            outerCircleStyle={{ borderColor: colors.theme_fg_three }} // style for outer animated circle
                            renderActiveText={false}
                            renderInActiveText={false}
                            switchLeftPx={2} // denominator for logic when sliding to TRUE position. Higher number = more space from RIGHT of the circle to END of the slider
                            switchRightPx={2} // denominator for logic when sliding to FALSE position. Higher number = more space from LEFT of the circle to BEGINNING of the slider
                            switchWidthMultiplier={2} // multiplied by the `circleSize` prop to calculate total width of the Switch
                            switchBorderRadius={30} // Sets the border Radius of the switch slider. If unset, it remains the circleSize.
                        />
                        <View style={{ margin: 10 }} />
                        <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: normal }}>Annually</Text>
                    </View>
                    <View style={{ margin: 20 }} />
                    <View style={{ flex: 1, alignItems: 'center' }}>
                        <TouchableOpacity style={{ borderWidth: 1, borderColor: colors.grey, borderRadius: 10, padding: 20, width: 200, alignItems: 'center', justifyContent: 'center', backgroundColor: colors.lite_bg }}>
                            <Text style={{ color: colors.theme_fg_two, fontSize: f_30, fontFamily: bold }}>$39.99</Text>
                        </TouchableOpacity>
                    </View>
                </View>
                <View style={{ margin: 10 }} />
                <View>
                    <View style={{ flexDirection: 'row', width: '100%', borderBottomWidth: 2, padding: 20, borderColor: colors.lite_bg }}>
                        <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                            <View style={{ width: 25, height: 25 }} >
                                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={check_icon} />
                            </View>
                        </View>
                        <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily: normal }}>50% off rides</Text>
                        </View>
                    </View>
                    <View style={{ flexDirection: 'row', width: '100%', borderBottomWidth: 2, padding: 20, borderColor: colors.lite_bg }}>
                        <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                            <View style={{ width: 25, height: 25 }} >
                                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={check_icon} />
                            </View>
                        </View>
                        <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily: normal }}>Free ride cancellations</Text>
                        </View>
                    </View>
                    <View style={{ flexDirection: 'row', width: '100%', borderBottomWidth: 2, padding: 20, borderColor: colors.lite_bg }}>
                        <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                            <View style={{ width: 25, height: 25 }} >
                                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={check_icon} />
                            </View>
                        </View>
                        <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily: normal }}>Priority Riding</Text>
                        </View>
                    </View>
                    <View style={{ flexDirection: 'row', width: '100%', borderBottomWidth: 2, padding: 20, borderColor: colors.lite_bg }}>
                        <View style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                            <View style={{ width: 25, height: 25 }} >
                                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={check_icon} />
                            </View>
                        </View>
                        <View style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily: normal }}>Upto 100 free rides a month</Text>
                        </View>
                    </View>
                </View>
                <View style={{ margin: 60 }} />
            </ScrollView>
            <View style={{ position: 'absolute', bottom: 0, width: '100%', height: 100, alignItems: 'center', justifyContent: 'center' }}>
                <TouchableOpacity activeOpacity={1} style={{ width: '90%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                    <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Upgrade to Pro</Text>
                </TouchableOpacity>
            </View>
        </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    header: {
        top: 0,
        left: 0,
        right: 0,
        zIndex: 16,
        height: 60,
        backgroundColor: colors.theme_bg_three,
        flexDirection: 'row',
        alignItems: 'center'
    },
});

export default Subscription;