import React from "react";
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
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth, bold, f_25, f_s } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';

const PaymentMethod = (props) => {
  const navigation = useNavigation();

  const go_back = () => {
    navigation.goBack();
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar
        backgroundColor={colors.theme_bg}
      />
      <View style={[styles.header]}>
        <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
          <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
        </TouchableOpacity>
      </View>
      <ScrollView>
        <View style={{ paddingLeft: 20, paddingRight: 20, paddingTop: 10, paddingBottom: 10 }}>
          <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_25, fontFamily: bold }}>Payment Methods</Text>
        </View>
        <View>
          <TouchableOpacity style={{ flexDirection: 'row', borderBottomWidth: 0.2, padding: 20, borderColor: colors.grey }}>
            <View style={{ width: '20%', alignItems: 'center', justifyContent: 'center' }}>
              <View style={{ height: 50, width: 50 }} >
                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={require(".././assets/img/temp/credit.png")} />
              </View>
            </View>
            <View style={{ margin: '5%' }} />
            <View style={{ width: '75%', alignItems: 'flex-start', justifyContent: 'center' }}>
              <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: bold }}>Razorpay</Text>
            </View>
          </TouchableOpacity>
          <TouchableOpacity style={{ flexDirection: 'row', borderBottomWidth: 0.5, padding: 20, borderColor: 'grey' }}>
            <View style={{ width: '20%', alignItems: 'center', justifyContent: 'center' }}>
              <View style={{ height: 50, width: 50 }} >
                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={require(".././assets/img/temp/credit.png")} />
              </View>
            </View>
            <View style={{ margin: '5%' }} />
            <View style={{ width: '75%', alignItems: 'flex-start', justifyContent: 'center' }}>
              <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: bold }}>Paypal</Text>
            </View>
          </TouchableOpacity>
          <TouchableOpacity style={{ flexDirection: 'row', borderBottomWidth: 0.5, padding: 20, borderColor: 'grey' }}>
            <View style={{ width: '20%', alignItems: 'center', justifyContent: 'center' }}>
              <View style={{ height: 50, width: 50 }} >
                <Image style={{ height: undefined, width: undefined, flex: 1 }} source={require(".././assets/img/temp/credit.png")} />
              </View>
            </View>
            <View style={{ margin: '5%' }} />
            <View style={{ width: '75%', alignItems: 'flex-start', justifyContent: 'center' }}>
              <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: bold }}>Paypal</Text>
            </View>
          </TouchableOpacity>

        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    ...StyleSheet.absoluteFillObject,
    height: screenHeight,
    width: screenWidth,
    backgroundColor: colors.lite_bg,
  },
  header: {
    height: 60,
    backgroundColor: colors.lite_bg,
    flexDirection: 'row',
    alignItems: 'center'
  },
});

export default PaymentMethod;