<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\AIPlatformNotebooks;

class GceSetup extends \Google\Collection
{
  protected $collection_key = 'tags';
  /**
   * @var AcceleratorConfig[]
   */
  public $acceleratorConfigs;
  protected $acceleratorConfigsType = AcceleratorConfig::class;
  protected $acceleratorConfigsDataType = 'array';
  /**
   * @var BootDisk
   */
  public $bootDisk;
  protected $bootDiskType = BootDisk::class;
  protected $bootDiskDataType = '';
  /**
   * @var ContainerImage
   */
  public $containerImage;
  protected $containerImageType = ContainerImage::class;
  protected $containerImageDataType = '';
  /**
   * @var DataDisk[]
   */
  public $dataDisks;
  protected $dataDisksType = DataDisk::class;
  protected $dataDisksDataType = 'array';
  /**
   * @var bool
   */
  public $disablePublicIp;
  /**
   * @var bool
   */
  public $enableIpForwarding;
  /**
   * @var GPUDriverConfig
   */
  public $gpuDriverConfig;
  protected $gpuDriverConfigType = GPUDriverConfig::class;
  protected $gpuDriverConfigDataType = '';
  /**
   * @var string
   */
  public $machineType;
  /**
   * @var string[]
   */
  public $metadata;
  /**
   * @var NetworkInterface[]
   */
  public $networkInterfaces;
  protected $networkInterfacesType = NetworkInterface::class;
  protected $networkInterfacesDataType = 'array';
  /**
   * @var ServiceAccount[]
   */
  public $serviceAccounts;
  protected $serviceAccountsType = ServiceAccount::class;
  protected $serviceAccountsDataType = 'array';
  /**
   * @var ShieldedInstanceConfig
   */
  public $shieldedInstanceConfig;
  protected $shieldedInstanceConfigType = ShieldedInstanceConfig::class;
  protected $shieldedInstanceConfigDataType = '';
  /**
   * @var string[]
   */
  public $tags;
  /**
   * @var VmImage
   */
  public $vmImage;
  protected $vmImageType = VmImage::class;
  protected $vmImageDataType = '';

  /**
   * @param AcceleratorConfig[]
   */
  public function setAcceleratorConfigs($acceleratorConfigs)
  {
    $this->acceleratorConfigs = $acceleratorConfigs;
  }
  /**
   * @return AcceleratorConfig[]
   */
  public function getAcceleratorConfigs()
  {
    return $this->acceleratorConfigs;
  }
  /**
   * @param BootDisk
   */
  public function setBootDisk(BootDisk $bootDisk)
  {
    $this->bootDisk = $bootDisk;
  }
  /**
   * @return BootDisk
   */
  public function getBootDisk()
  {
    return $this->bootDisk;
  }
  /**
   * @param ContainerImage
   */
  public function setContainerImage(ContainerImage $containerImage)
  {
    $this->containerImage = $containerImage;
  }
  /**
   * @return ContainerImage
   */
  public function getContainerImage()
  {
    return $this->containerImage;
  }
  /**
   * @param DataDisk[]
   */
  public function setDataDisks($dataDisks)
  {
    $this->dataDisks = $dataDisks;
  }
  /**
   * @return DataDisk[]
   */
  public function getDataDisks()
  {
    return $this->dataDisks;
  }
  /**
   * @param bool
   */
  public function setDisablePublicIp($disablePublicIp)
  {
    $this->disablePublicIp = $disablePublicIp;
  }
  /**
   * @return bool
   */
  public function getDisablePublicIp()
  {
    return $this->disablePublicIp;
  }
  /**
   * @param bool
   */
  public function setEnableIpForwarding($enableIpForwarding)
  {
    $this->enableIpForwarding = $enableIpForwarding;
  }
  /**
   * @return bool
   */
  public function getEnableIpForwarding()
  {
    return $this->enableIpForwarding;
  }
  /**
   * @param GPUDriverConfig
   */
  public function setGpuDriverConfig(GPUDriverConfig $gpuDriverConfig)
  {
    $this->gpuDriverConfig = $gpuDriverConfig;
  }
  /**
   * @return GPUDriverConfig
   */
  public function getGpuDriverConfig()
  {
    return $this->gpuDriverConfig;
  }
  /**
   * @param string
   */
  public function setMachineType($machineType)
  {
    $this->machineType = $machineType;
  }
  /**
   * @return string
   */
  public function getMachineType()
  {
    return $this->machineType;
  }
  /**
   * @param string[]
   */
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }
  /**
   * @return string[]
   */
  public function getMetadata()
  {
    return $this->metadata;
  }
  /**
   * @param NetworkInterface[]
   */
  public function setNetworkInterfaces($networkInterfaces)
  {
    $this->networkInterfaces = $networkInterfaces;
  }
  /**
   * @return NetworkInterface[]
   */
  public function getNetworkInterfaces()
  {
    return $this->networkInterfaces;
  }
  /**
   * @param ServiceAccount[]
   */
  public function setServiceAccounts($serviceAccounts)
  {
    $this->serviceAccounts = $serviceAccounts;
  }
  /**
   * @return ServiceAccount[]
   */
  public function getServiceAccounts()
  {
    return $this->serviceAccounts;
  }
  /**
   * @param ShieldedInstanceConfig
   */
  public function setShieldedInstanceConfig(ShieldedInstanceConfig $shieldedInstanceConfig)
  {
    $this->shieldedInstanceConfig = $shieldedInstanceConfig;
  }
  /**
   * @return ShieldedInstanceConfig
   */
  public function getShieldedInstanceConfig()
  {
    return $this->shieldedInstanceConfig;
  }
  /**
   * @param string[]
   */
  public function setTags($tags)
  {
    $this->tags = $tags;
  }
  /**
   * @return string[]
   */
  public function getTags()
  {
    return $this->tags;
  }
  /**
   * @param VmImage
   */
  public function setVmImage(VmImage $vmImage)
  {
    $this->vmImage = $vmImage;
  }
  /**
   * @return VmImage
   */
  public function getVmImage()
  {
    return $this->vmImage;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GceSetup::class, 'Google_Service_AIPlatformNotebooks_GceSetup');
