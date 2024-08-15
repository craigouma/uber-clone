import React from "react";
import {
  TouchableOpacity,
  StyleSheet,
  View,
  SafeAreaView,
  ScrollView,
  StatusBar
} from "react-native";
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';

const PrivacyPolicies = (props) => {
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

      </ScrollView>
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
    backgroundColor: colors.lite_bg,
    flexDirection: 'row',
    alignItems: 'center'
  },
});

export default PrivacyPolicies;