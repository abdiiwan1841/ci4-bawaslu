import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable

WebUI.openBrowser('http://localhost:8080/login')

WebUI.setText(findTestObject('Login/FieldUsername'), 'admin')

WebUI.setText(findTestObject('Login/FieldPassword'), 'admin')

WebUI.click(findTestObject('Login/ButtonLogin'))

WebUI.click(findTestObject('Menu/Menu Jenis Temuan'))

WebUI.click(findTestObject('Jenis Temuan/ButtonEdit'))

WebUI.setText(findTestObject('Jenis Temuan/From Create/Kode'), '115')

WebUI.setText(findTestObject('Jenis Temuan/From Create/Deskripsi'), 'Tidak menguntungkan Negara')

WebUI.selectOptionByValue(findTestObject('Jenis Temuan/From Create/Parent'), 'bdff7538-abbb-1192-4033-5432ed1589b5', false)

WebUI.click(findTestObject('Jenis Temuan/From Create/ButtonSubmit'), FailureHandling.STOP_ON_FAILURE)

