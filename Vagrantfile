# -*- mode: ruby -*-

Vagrant::Config.run do |config|
  config.vm.box = "quantal64"
  config.vm.forward_port 80, 8080
  config.vm.network :hostonly, "192.168.50.9"

  config.vm.share_folder "vagrant-root", "/vagrant", ".", :nfs => true
  config.vm.share_folder "salt_file_root", "/srv", "./salt/root"

  config.vm.provision :salt do |salt|
    salt.run_highstate = true
    salt.minion_config = "salt/minion.conf"
  end
end
